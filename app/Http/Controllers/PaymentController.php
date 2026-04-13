<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class PaymentController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey = config('app.midtrans_server_key', env('MIDTRANS_SERVER_KEY'));
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS', true);
    }

    public function show(Pesanan $pesanan)
    {
        if ($pesanan->payment && $pesanan->payment->payment_status === 'settlement') {
            return redirect()->route('canteen.index')->with('success', 'Order is already paid.');
        }

        return view('canteen.payment', compact('pesanan'));
    }

    public function success(Pesanan $pesanan)
    {
        if (!$pesanan->payment || $pesanan->status_bayar != 1) {
            return redirect()->route('payment.show', ['pesanan' => $pesanan->idpesanan]);
        }
        return view('canteen.success', compact('pesanan'));
    }

    /**
     * Generate a QR code PNG for the given idpesanan.
     * Route: GET /canteen/qrcode/{idpesanan}
     */
    public function qrCode(Pesanan $pesanan)
    {
        $writer = new PngWriter();

        $qrCode = new QrCode(
            data: 'PESANAN:' . $pesanan->idpesanan,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255),
        );

        $result = $writer->write($qrCode);

        return response($result->getString(), 200)
            ->header('Content-Type', $result->getMimeType())
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function create(Request $request)
    {
        $request->validate([
            'idpesanan' => 'required|exists:pesanan,idpesanan',
            'payment_method' => 'required|in:bca,bni,bri,qris',
        ]);

        $pesanan = Pesanan::with('detailPesanan.menu')->findOrFail($request->idpesanan);

        if ($pesanan->payment && in_array($pesanan->payment->payment_status, ['pending', 'settlement'])) {
            return redirect()->back()->with('error', 'Payment is already processing or paid.');
        }

        $orderId = 'ORD-' . $pesanan->idpesanan . '-' . time();
        $grossAmount = $pesanan->total;

        $itemDetails = [];
        foreach ($pesanan->detailPesanan as $detail) {
            $itemDetails[] = [
                'id' => $detail->idmenu,
                'price' => $detail->harga,
                'quantity' => $detail->jumlah,
                'name' => substr($detail->menu->nama_menu, 0, 50),
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $pesanan->nama,
                'email' => 'guest@canteen.local',
                'phone' => '08123456789',
            ],
            'item_details' => $itemDetails,
        ];

        if ($request->payment_method === 'qris') {
            $params['payment_type'] = 'gopay';
            $params['gopay'] = [
                'enable_callback' => true,
                'callback_url' => url('/')
            ];
        } else {
            $params['payment_type'] = 'bank_transfer';
            $params['bank_transfer'] = [
                'bank' => $request->payment_method,
            ];
        }

        try {
            $response = \Midtrans\CoreApi::charge($params);
            
            $pesanan->update(['metode_bayar' => strtoupper($request->payment_method)]);

            $paymentData = [
                'idpesanan' => $pesanan->idpesanan,
                'transaction_id' => $response->transaction_id,
                'payment_type' => $response->payment_type,
                'gross_amount' => (int) round($response->gross_amount),
                'payment_status' => $response->transaction_status,
                'midtrans_response' => $response,
            ];

            if (isset($response->va_numbers) && count($response->va_numbers) > 0) {
                $paymentData['va_number'] = $response->va_numbers[0]->va_number;
                $paymentData['bank'] = $response->va_numbers[0]->bank;
            }

            if ($request->payment_method === 'qris' && isset($response->actions)) {
                foreach ($response->actions as $action) {
                    if ($action->name === 'generate-qr-code') {
                        $paymentData['qr_code_url'] = $action->url;
                    }
                }
            }

            Payment::updateOrCreate(
                ['idpesanan' => $pesanan->idpesanan],
                $paymentData
            );

            return redirect()->route('payment.show', ['pesanan' => $pesanan->idpesanan])->with('success', 'Payment request created.');

        } catch (\Exception $e) {
            Log::error('Midtrans Charge Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not create payment: ' . $e->getMessage());
        }
    }

    public function checkStatus(Pesanan $pesanan)
    {
        if ($pesanan->payment) {
            try {
                $status = \Midtrans\Transaction::status($pesanan->payment->transaction_id);
                $this->handlePaymentStatus($status);
            } catch (\Exception $e) {
                // Ignore status check errors on frontend
            }
        }
        return response()->json([
            'status' => $pesanan->fresh()->status_bayar
        ]);
    }

    public function notification(Request $request)
    {
        try {
            $notification = new \Midtrans\Notification();
            $this->handlePaymentStatus($notification);
            return response()->json(['message' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'error', 'details' => $e->getMessage()], 500);
        }
    }

    private function handlePaymentStatus($notification)
    {
        $transaction_status = $notification->transaction_status;
        $fraud_status = $notification->fraud_status;
        $order_id = $notification->order_id; // e.g ORD-IDPESANAN-TIME

        $parts = explode('-', $order_id);
        $idpesanan = $parts[1] ?? null;

        if (!$idpesanan) return;

        $pesanan = Pesanan::find($idpesanan);
        if (!$pesanan) return;

        $payment = $pesanan->payment;
        if ($payment) {
            $payment->update([
                'payment_status' => $transaction_status,
                'fraud_status' => $fraud_status ?? null,
            ]);
        }

        if ($transaction_status == 'capture' || $transaction_status == 'settlement') {
            if ($fraud_status == 'challenge') {
                $pesanan->update(['status_bayar' => 0]); // pending
            } else {
                $pesanan->update(['status_bayar' => 1]); // lunas
                if ($payment) $payment->update(['paid_at' => now()]);
            }
        } else if ($transaction_status == 'cancel' || $transaction_status == 'deny') {
            $pesanan->update(['status_bayar' => 3]); // cancelled
        } else if ($transaction_status == 'expire') {
            $pesanan->update(['status_bayar' => 2]); // expired
        } else if ($transaction_status == 'pending') {
            $pesanan->update(['status_bayar' => 0]); // pending
        }
    }
}
