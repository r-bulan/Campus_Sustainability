<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Energy',     'description' => 'Initiatives to save power',       'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Water',      'description' => 'Water conservation programs',     'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Recycling',  'description' => 'Recycling drives on campus',      'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Awareness',  'description' => 'Awareness campaigns',             'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'Gardening',  'description' => 'Planting trees and gardens',      'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
