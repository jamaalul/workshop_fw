<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\NfcCard;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NfcController extends Controller
{
    /**
     * Tampilkan halaman scanner NFC.
     */
    public function index()
    {
        return view('nfc.scanner');
    }

    /**
     * Proses scan NFC — terima JSON { serial_number, isi }.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string|min:1',
        ]);

        $serialNumber = trim($request->input('serial_number'));

        if (empty($serialNumber)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Serial number kosong. Pastikan kartu NFC memiliki data.',
            ], 422);
        }

        // Cari kartu berdasarkan serial_number
        $card = NfcCard::where('serial_number', $serialNumber)->first();

        if (!$card) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kartu tidak dikenal (SN: ' . substr($serialNumber, 0, 20) . '). Silakan daftarkan terlebih dahulu.',
            ]);
        }

        $today = Carbon::today()->toDateString();
        $now   = Carbon::now();

        // Cek apakah sudah absen hari ini
        $existing = Attendance::where('nfc_card_id', $card->id)
            ->where('tanggal', $today)
            ->first();

        if ($existing) {
            return response()->json([
                'status'  => 'warning',
                'message' => 'Sudah absen hari ini',
                'nama'    => $card->nama_mahasiswa,
                'waktu'   => $existing->waktu,
            ]);
        }

        // Tentukan status: hadir jika sebelum 08:00, terlambat jika setelah
        $batasWaktu = Carbon::today()->setTime(8, 0, 0);
        $status     = $now->lte($batasWaktu) ? 'hadir' : 'terlambat';

        // Simpan absensi
        $attendance = Attendance::create([
            'nfc_card_id' => $card->id,
            'tanggal'     => $today,
            'waktu'       => $now->format('H:i:s'),
            'status'      => $status,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Absensi berhasil',
            'nama'    => $card->nama_mahasiswa,
            'waktu'   => $attendance->waktu,
        ]);
    }

    /**
     * Tampilkan halaman registrasi kartu NFC.
     */
    public function register()
    {
        return view('nfc.register');
    }

    /**
     * Simpan kartu NFC baru.
     */
    public function storeCard(Request $request)
    {
        $request->validate([
            'serial_number'  => 'required|string|unique:nfc_cards,serial_number',
            'nama_mahasiswa' => 'required|string|max:255',
        ]);

        NfcCard::create([
            'serial_number'  => $request->input('serial_number'),
            'nama_mahasiswa' => $request->input('nama_mahasiswa'),
        ]);

        return redirect('/nfc/register')->with('success', 'Kartu berhasil didaftarkan!');
    }

    /**
     * Tampilkan riwayat absensi.
     */
    public function history()
    {
        $attendances = Attendance::with('nfcCard')
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->get();

        return view('nfc.history', compact('attendances'));
    }
}
