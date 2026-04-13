<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // Submenu 1 – Data Customer (list all)
    // ─────────────────────────────────────────────────────────────────────────
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customer.index', compact('customers'));
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        // Remove physical file if it exists
        if ($customer->photo_path && file_exists(public_path($customer->photo_path))) {
            unlink(public_path($customer->photo_path));
        }

        $customer->delete();
        return redirect()->route('customer.index')->with('success', 'Data customer berhasil dihapus.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Submenu 2 – Tambah Customer 1 (BLOB storage)
    // ─────────────────────────────────────────────────────────────────────────
    public function createBlob()
    {
        return view('customer.create_blob');
    }

    public function storeBlob(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:255',
            'email'      => 'nullable|email|max:255',
            'telepon'    => 'nullable|string|max:50',
            'photo_data' => 'nullable|string', // base64 data URL
        ]);

        $blobData = null;
        if ($request->filled('photo_data')) {
            // Strip the data URL prefix: data:image/jpeg;base64,<base64data>
            $dataUrl = $request->photo_data;
            if (preg_match('/^data:image\/\w+;base64,/', $dataUrl)) {
                $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $dataUrl);
                
                // Decode from base64 first
                $binaryData = base64_decode($base64);
                
                // For PostgreSQL bytea columns, we must insert the data as a hex string 
                // prefixed with \x to avoid UTF-8 encoding errors (SQLSTATE 22021).
                // If using MySQL, raw binary would work, but \x format might be treated literally.
                // However, since we are connected to pgsql as per the error, we use the hex format.
                if (config('database.default') === 'pgsql') {
                    $blobData = '\x' . bin2hex($binaryData);
                } else {
                    $blobData = $binaryData;
                }
            }
        }

        Customer::create([
            'nama'       => $request->nama,
            'email'      => $request->email,
            'telepon'    => $request->telepon,
            'photo_blob' => $blobData,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan (blob).');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Submenu 3 – Tambah Customer 2 (File storage)
    // ─────────────────────────────────────────────────────────────────────────
    public function createFile()
    {
        return view('customer.create_file');
    }

    public function storeFile(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:255',
            'email'      => 'nullable|email|max:255',
            'telepon'    => 'nullable|string|max:50',
            'photo_data' => 'nullable|string', // base64 data URL
        ]);

        $filePath = null;
        if ($request->filled('photo_data')) {
            $dataUrl = $request->photo_data;
            if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $matches)) {
                $ext      = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
                $base64   = preg_replace('/^data:image\/\w+;base64,/', '', $dataUrl);
                $binary   = base64_decode($base64);

                $uploadDir = public_path('uploads/customers');
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $filename = 'customer_' . time() . '_' . Str::random(8) . '.' . $ext;
                file_put_contents($uploadDir . '/' . $filename, $binary);
                $filePath = 'uploads/customers/' . $filename;
            }
        }

        Customer::create([
            'nama'       => $request->nama,
            'email'      => $request->email,
            'telepon'    => $request->telepon,
            'photo_path' => $filePath,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan (file).');
    }
}
