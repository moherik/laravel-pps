<?php

namespace Database\Seeders;

use App\Models\Vote;
use Illuminate\Database\Seeder;

class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vote::create([
            'room_id' => 1,
            'candidate_id' => 1,
            'total' => 120
        ]);

        Vote::create([
            'room_id' => 1,
            'candidate_id' => 2,
            'total' => 50
        ]);

        Vote::create([
            'room_id' => 1,
            'total' => 10
        ]);
    }
}
