<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data =
        [
            [
            'barang_id' => 1,
            'kategori_id' =>1,
            'barang_kode'=> 'FNB1',
            'barang_nama' => 'Mie Instan',
            'harga_beli' => 30000,
            'harga_jual' => 40000 ,
            ],
            [
            'barang_id' => 2,
            'kategori_id' =>1,
            'barang_kode'=> 'FNB2',
            'barang_nama' => 'Krimer',
            'harga_beli' => 25000,
            'harga_jual' => 32000 ,
            ],
            [
            'barang_id' => 3,
            'kategori_id' =>1,
            'barang_kode'=> 'FNB3',
            'barang_nama' => 'Nastar',
            'harga_beli' => 45000,
            'harga_jual' => 60000 ,
            ],
            [
            'barang_id' => 4,
            'kategori_id' =>1,
            'barang_kode'=> 'FNB4',
            'barang_nama' => 'Keju Mozarella',
            'harga_beli' => 20000,
            'harga_jual' => 30000 ,
            ],
            [
            'barang_id' => 5,
            'kategori_id' =>1,
            'barang_kode'=> 'FNB5',
            'barang_nama' => 'Beef Slice',
            'harga_beli' => 60000,
            'harga_jual' => 75000 ,
            ],
            [
            'barang_id' => 6,
            'kategori_id' =>2,
            'barang_kode'=> 'BNH1',
            'barang_nama' => 'Toner',
            'harga_beli' => 30000,
            'harga_jual' => 40000 ,
            ],
            [
            'barang_id' => 7,
            'kategori_id' =>2,
            'barang_kode'=> 'BNH2',
            'barang_nama' => 'Moisturizer',
            'harga_beli' => 50000,
            'harga_jual' => 55000 ,
            ],
            [
            'barang_id' => 8,
            'kategori_id' =>2,
            'barang_kode'=> 'BNH3',
            'barang_nama' => 'Body Wash',
            'harga_beli' => 15000,
            'harga_jual' => 20000 ,
            ],
            [
            'barang_id' => 9,
            'kategori_id' =>2,
            'barang_kode'=> 'BNH4',
            'barang_nama' => 'Minyak Urut',
            'harga_beli' => 10000,
            'harga_jual' => 15000 ,
            ],
            [
            'barang_id' => 10,
            'kategori_id' =>2,
            'barang_kode'=> 'BNH5',
            'barang_nama' => 'Eyeshadow',
            'harga_beli' => 65000,
            'harga_jual' => 80000 ,
            ],
            [
            'barang_id' => 11,
            'kategori_id' =>3,
            'barang_kode'=> 'HNC1',
            'barang_nama' => 'Karbol Pel',
            'harga_beli' => 20000,
            'harga_jual' => 25000 ,
            ],
            [
            'barang_id' => 12,
            'kategori_id' =>3,
            'barang_kode'=> 'HNC2',
            'barang_nama' => 'Deterjen Cair',
            'harga_beli' => 15000,
            'harga_jual' => 20000 ,   
            ],
            [
            'barang_id' => 13,
            'kategori_id' =>3,
            'barang_kode'=> 'HNC3',
            'barang_nama' => 'Karpet Bludru',
            'harga_beli' => 100000,
            'harga_jual' => 150000 ,
            ],
            [
            'barang_id' => 14,
            'kategori_id' =>3,
            'barang_kode'=> 'HNC4',
            'barang_nama' => 'Kemoceng',
            'harga_beli' => 7000,
            'harga_jual' => 10000 ,
            ],
            [
            'barang_id' => 15,
            'kategori_id' =>3,
            'barang_kode'=> 'HNC5',
            'barang_nama' => 'Pengharum Ruangan',
            'harga_beli' => 30000,
            'harga_jual' => 35000 ,
            ],
        ];
        DB::table('m_barang')->insert($data);
    }
}
