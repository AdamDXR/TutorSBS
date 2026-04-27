<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterTutorial;
use App\Models\DetailTutorial;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Data dummy master_tutorial untuk keperluan demo ---
        $master1 = MasterTutorial::create([
            'judul'            => 'Tutorial Laravel Dasar',
            'kode_makul'       => 'PWL',
            'url_presentation' => 'laravel-dasar',
            'url_finished'     => 'laravel-dasar-pdf',
            'creator_email'    => 'aprilyani.safitri@gmail.com',
        ]);

        $master2 = MasterTutorial::create([
            'judul'            => 'Tutorial OOP PHP',
            'kode_makul'       => 'PBO',
            'url_presentation' => 'oop-php',
            'url_finished'     => 'oop-php-pdf',
            'creator_email'    => 'aprilyani.safitri@gmail.com',
        ]);

        // --- Data dummy detail_tutorial (kombinasi status show & hide) ---
        DetailTutorial::insert([
            [
                'master_tutorial_id' => $master1->id,
                'text'    => 'Langkah 1: Install Laravel via Composer.',
                'gambar'  => null,
                'code'    => 'composer create-project laravel/laravel app',
                'url'     => 'https://laravel.com/docs',
                'order'   => 1,
                'status'  => 'show',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'master_tutorial_id' => $master1->id,
                'text'    => 'Langkah 2: Konfigurasi file .env untuk database.',
                'gambar'  => null,
                'code'    => 'DB_DATABASE=tutorial_db',
                'url'     => null,
                'order'   => 2,
                'status'  => 'show',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'master_tutorial_id' => $master1->id,
                'text'    => 'Catatan internal: draft materi belum final.',
                'gambar'  => null,
                'code'    => null,
                'url'     => null,
                'order'   => 3,
                'status'  => 'hide',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
