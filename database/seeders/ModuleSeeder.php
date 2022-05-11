<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->insert([
            [
                'id' => 1,
                'name' => 'AAW',
            ],
            [
                'id' => 2,
                'name' => 'CSE',
            ],
            [
                'id' => 3,
                'name' => 'MSSC',
            ],
            [
                'id' => 4,
                'name' => 'IGR',
            ],
            [
                'id' => 5,
                'name' => 'MTS',
            ],
            [
                'id' => 6,
                'name' => 'GCC',
            ],
        ]);
    }
}
