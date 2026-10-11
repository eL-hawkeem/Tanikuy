<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'kode_supplier' => 'SUP-0001',
                'nama' => 'Haji Mansur',
                'perusahaan' => 'PT Petro Tani Nusantara',
                'telepon' => '081234567890',
                'email' => 'sales@petrotani.co.id',
                'alamat' => 'Jl. Raya Industri Pertanian No. 45',
                'kota' => 'Gresik',
                'catatan' => 'Pemasok utama pupuk bersubsidi dan non-subsidi',
                'status' => 'active',
            ],
            [
                'kode_supplier' => 'SUP-0002',
                'nama' => 'Budi Santoso',
                'perusahaan' => 'CV Benih Makmur Unggul',
                'telepon' => '082198765432',
                'email' => 'kontak@benihmakmur.com',
                'alamat' => 'Jl. Agrowisata Raya Kav. 12',
                'kota' => 'Malang',
                'catatan' => 'Penyedia bibit padi bersertifikat BPSB',
                'status' => 'active',
            ],
            [
                'kode_supplier' => 'SUP-0003',
                'nama' => 'Agus Wahyudi',
                'perusahaan' => 'UD Mekar Jaya Tools',
                'telepon' => '085712348765',
                'email' => 'ud.mekarjaya@gmail.com',
                'alamat' => 'Sentra Logam Pertanian Blok B3',
                'kota' => 'Klaten',
                'catatan' => 'Produsen cangkul baja asli dan tangki semprot',
                'status' => 'active',
            ],
            [
                'kode_supplier' => 'SUP-0004',
                'nama' => 'Siti Rohmah',
                'perusahaan' => 'PT Agro Kimia Farma',
                'telepon' => '087865432109',
                'email' => 'order@agrokimiaproteksi.com',
                'alamat' => 'Kawasan Industri Cikarang Blok D10',
                'kota' => 'Bekasi',
                'catatan' => 'Distributor resmi pestisida dan fungisida',
                'status' => 'active',
            ],
        ];

        foreach ($suppliers as $sup) {
            Supplier::updateOrCreate(
                ['kode_supplier' => $sup['kode_supplier']],
                $sup
            );
        }
    }
}
