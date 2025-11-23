<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InitiativeSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        DB::table('initiatives')->insert([
            ['id' => 1, 'title' => 'Solar Panel Setup',    'description' => null, 'category_id' => 1, 'start_date' => '2025-11-01', 'end_date' => '2025-11-10', 'impact_score' => 50, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'title' => 'Rainwater Harvest',    'description' => null, 'category_id' => 2, 'start_date' => '2025-11-05', 'end_date' => '2025-11-15', 'impact_score' => 30, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'title' => 'Recycling Drive',      'description' => null, 'category_id' => 3, 'start_date' => '2025-11-07', 'end_date' => '2025-11-12', 'impact_score' => 20, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'title' => 'Tree Planting',        'description' => null, 'category_id' => 5, 'start_date' => '2025-11-10', 'end_date' => '2025-11-20', 'impact_score' => 40, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'title' => 'Awareness Seminar',    'description' => null, 'category_id' => 4, 'start_date' => '2025-11-03', 'end_date' => '2025-11-05', 'impact_score' => 15, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
