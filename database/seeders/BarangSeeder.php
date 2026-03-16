<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = [
            [
                'kode' => 'BRG001',
                'nama' => 'Laptop Asus VivoBook 15',
                'harga' => 5500000,
            ],
            [
                'kode' => 'BRG002',
                'nama' => 'Smartphone Samsung Galaxy A54',
                'harga' => 4200000,
            ],
            [
                'kode' => 'BRG003',
                'nama' => 'Wireless Mouse Logitech M235',
                'harga' => 150000,
            ],
            [
                'kode' => 'BRG004',
                'nama' => 'Keyboard Mechanical Rexus Daxa M84',
                'harga' => 650000,
            ],
            [
                'kode' => 'BRG005',
                'nama' => 'Monitor LG 24 Inch Full HD',
                'harga' => 1800000,
            ],
            [
                'kode' => 'BRG006',
                'nama' => 'Headphone Sony WH-1000XM4',
                'harga' => 3200000,
            ],
            [
                'kode' => 'BRG007',
                'nama' => 'Flashdisk Kingston 32GB',
                'harga' => 45000,
            ],
            [
                'kode' => 'BRG008',
                'nama' => 'Hard Disk Eksternal WD 1TB',
                'harga' => 550000,
            ],
            [
                'kode' => 'BRG009',
                'nama' => 'Webcam Logitech C920',
                'harga' => 750000,
            ],
            [
                'kode' => 'BRG010',
                'nama' => 'Printer Canon Pixma G3010',
                'harga' => 1450000,
            ],
            [
                'kode' => 'BRG011',
                'nama' => 'Router TP-Link Archer C50',
                'harga' => 350000,
            ],
            [
                'kode' => 'BRG012',
                'nama' => 'UPS APC Back-UPS 600VA',
                'harga' => 850000,
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
