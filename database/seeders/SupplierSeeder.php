<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = 
        [
            [
                'supplier_id'=> 1,
                'supplier_kode' => 'SUP1',
                'supplier_nama' => 'CV.SUTIWIJAYA',
                'supplier_alamat' => 'Jl. Sulfat No 59-B',
            ],
            [
                'supplier_id'=> 2,
                'supplier_kode' => 'SUP2',
                'supplier_nama' => 'MARSYA FASHION',
                'supplier_alamat' => 'Jl. Sebuku No 53',
            ],
            [
                'supplier_id'=> 3,
                'supplier_kode' => 'SUP3',
                'supplier_nama' => 'PT. SRI REJEKI',
                'supplier_alamat' => 'Jl. Soekarno No 42-A',
            ],
        ];
        DB::table('m_supplier')->insert($data);
    }
}
