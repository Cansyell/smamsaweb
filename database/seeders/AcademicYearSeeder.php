<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;
use Carbon\Carbon;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data tahun ajaran
        $academicYears = [
            [
                'year' => '2022/2023',
                'name' => 'Tahun Ajaran 2022/2023',
                'start_date' => '2022-07-18',
                'end_date' => '2023-06-30',
                'is_active' => false,
                'description' => 'Tahun ajaran 2022/2023',
            ],
            [
                'year' => '2023/2024',
                'name' => 'Tahun Ajaran 2023/2024',
                'start_date' => '2023-07-17',
                'end_date' => '2024-06-28',
                'is_active' => false,
                'description' => 'Tahun ajaran 2023/2024',
            ],
            [
                'year' => '2024/2025',
                'name' => 'Tahun Ajaran 2024/2025',
                'start_date' => '2024-07-15',
                'end_date' => '2025-06-27',
                'is_active' => true, // Tahun ajaran aktif saat ini
                'description' => 'Tahun ajaran aktif 2024/2025',
            ],
            [
                'year' => '2025/2026',
                'name' => 'Tahun Ajaran 2025/2026',
                'start_date' => '2025-07-14',
                'end_date' => '2026-06-26',
                'is_active' => false,
                'description' => 'Tahun ajaran mendatang 2025/2026',
            ],
        ];

        foreach ($academicYears as $academicYear) {
            AcademicYear::create($academicYear);
        }

        $this->command->info('Academic years seeded successfully!');
    }
}