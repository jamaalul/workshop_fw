<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CanteenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            ['nama_vendor' => 'Kantin Sehat'],
            ['nama_vendor' => 'Kantin Berkah'],
            ['nama_vendor' => 'Dapur Mama'],
        ];

        foreach ($vendors as $v) {
            $vendor = \App\Models\Vendor::create($v);

            if ($vendor->nama_vendor == 'Kantin Sehat') {
                \App\Models\Menu::create([
                    'nama_menu' => 'Nasi Goreng Ayam',
                    'harga' => 15000,
                    'path_gambar' => 'nasi-goreng.jpg',
                    'idvendor' => $vendor->idvendor
                ]);
                \App\Models\Menu::create([
                    'nama_menu' => 'Mie Goreng Spesial',
                    'harga' => 12000,
                    'path_gambar' => 'mie-goreng.jpg',
                    'idvendor' => $vendor->idvendor
                ]);
            } elseif ($vendor->nama_vendor == 'Kantin Berkah') {
                \App\Models\Menu::create([
                    'nama_menu' => 'Ayam Geprek',
                    'harga' => 18000,
                    'path_gambar' => 'ayam-geprek.jpg',
                    'idvendor' => $vendor->idvendor
                ]);
                \App\Models\Menu::create([
                    'nama_menu' => 'Es Teh Manis',
                    'harga' => 5000,
                    'path_gambar' => 'es-teh.jpg',
                    'idvendor' => $vendor->idvendor
                ]);
            } else {
                \App\Models\Menu::create([
                    'nama_menu' => 'Soto Ayam',
                    'harga' => 15000,
                    'path_gambar' => 'soto.jpg',
                    'idvendor' => $vendor->idvendor
                ]);
            }
        }
    }
}
