<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nama' => 'Beras & Biji-bijian',
                'deskripsi' => 'Beras lokal, beras pandan wangi, ketan, dan gabah kering panen.',
                'warna' => '#F59E0B',
            ],
            [
                'nama' => 'Pupuk Organik & Kimia',
                'deskripsi' => 'Pupuk urea, NPK, TSP, kompos hayati, dan pupuk cair bio-organik.',
                'warna' => '#10B981',
            ],
            [
                'nama' => 'Benih & Bibit Pertanian',
                'deskripsi' => 'Benih padi unggul, bibit jagung hibrida, cabai, tomat, dan sayuran hijau.',
                'warna' => '#3B82F6',
            ],
            [
                'nama' => 'Pestisida & Proteksi Tanaman',
                'deskripsi' => 'Insektisida, fungisida, herbisida selektif, dan perekat perata semprot.',
                'warna' => '#EF4444',
            ],
            [
                'nama' => 'Peralatan & Mesin Pertanian',
                'deskripsi' => 'Tangki semprot manual/elektrik, cangkul baja, selang irigasi, dan parang.',
                'warna' => '#8B5CF6',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['nama' => $cat['nama']],
                [
                    'slug' => Str::slug($cat['nama']),
                    'deskripsi' => $cat['deskripsi'],
                    'warna' => $cat['warna'],
                    'is_active' => true,
                ]
            );
        }
    }
}
