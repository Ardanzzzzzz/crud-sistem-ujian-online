<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;

class ExamSeeder extends Seeder
{
    public function run()
    {
        Exam::create([
            'title' => 'Matematika Dasar',
            'description' => 'Ujian matematika dasar untuk kelas 10',
            'duration' => 60, // 60 menit
            'is_active' => true,
        ]);

        Exam::create([
            'title' => 'Bahasa Indonesia',
            'description' => 'Ujian bahasa Indonesia semester 1',
            'duration' => 90, // 90 menit
            'is_active' => true,
        ]);

        Exam::create([
            'title' => 'Sejarah Indonesia',
            'description' => 'Ujian sejarah Indonesia modern',
            'duration' => 45, // 45 menit
            'is_active' => false,
        ]);
    }
}