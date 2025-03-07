<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => 'Sports'],
            ['name' => 'E-book'],
            ['name' => 'Podcast'],
            ['name' => 'Arts'],
            ['name' => 'Music'],
        ];
        DB::table('stream_types')->insert($types);
    }
}
