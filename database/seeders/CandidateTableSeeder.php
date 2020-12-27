<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Database\Seeder;

class CandidateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Candidate::create([
            'room_id' => 1,
            'order' => 1,
            'name' => 'Spongebob & Patrick',
            'image' => 'photos/spongebob-patrick.jpg'
        ]);

        Candidate::create([
            'room_id' => 1,
            'order' => 3,
            'name' => 'Kerang Ajaib',
            'image' => 'photos/kerang-ajaib.jpg'
        ]);
    }
}
