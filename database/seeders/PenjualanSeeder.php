<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'penjualan_id' => 1,
                'user_id' => 3,
                'pembeli' => 'Marsya',
                'penjualan_kode' => 'P1',
                'penjualan_tanggal' => '2025-02-25 13:00:00',
            ],
            [
                'penjualan_id' => 2,
                'user_id' => 3,
                'pembeli' => 'Aurelia',
                'penjualan_kode' => 'P2',
                'penjualan_tanggal' => '2025-02-25 13:15:00',
            ],
            [
                'penjualan_id' => 3,
                'user_id' => 3,
                'pembeli' => 'Sefira',
                'penjualan_kode' => 'P3',
                'penjualan_tanggal' => '2025-02-25 13:30:00',
            ],
            [
                'penjualan_id' => 4,
                'user_id' => 3,
                'pembeli' => 'Mahardika',
                'penjualan_kode' => 'P4',
                'penjualan_tanggal' => '2025-02-25 13:45:00',
            ],
            [
                'penjualan_id' => 5,
                'user_id' => 3,
                'pembeli' => 'Ibu Sri',
                'penjualan_kode' => 'P5',
                'penjualan_tanggal' => '2025-02-25 14:00:00',
            ],
            [
                'penjualan_id' => 6,
                'user_id' => 3,
                'pembeli' => 'Aldevaro',
                'penjualan_kode' => 'P6',
                'penjualan_tanggal' => '2025-02-25 14:15:00',
            ],
            [
                'penjualan_id' => 7,
                'user_id' => 3,
                'pembeli' => 'Akbar',
                'penjualan_kode' => 'P7',
                'penjualan_tanggal' => '2025-02-25 14:30:00',
            ],
            [
                'penjualan_id' => 8,
                'user_id' => 3,
                'pembeli' => 'Salsa',
                'penjualan_kode' => 'P8',
                'penjualan_tanggal' => '2025-02-25 14:45:00',
            ],
            [
                'penjualan_id' => 9,
                'user_id' => 3,
                'pembeli' => 'Niken',
                'penjualan_kode' => 'P9',
                'penjualan_tanggal' => '2025-02-25 15:00:00',
            ],
            [
                'penjualan_id' => 10,
                'user_id' => 3,
                'pembeli' => 'Yansen',
                'penjualan_kode' => 'P10',
                'penjualan_tanggal' => '2025-02-25 15:15:00',
            ],
        ];
        DB::table('t_penjualan')->insert($data);
    }
}
