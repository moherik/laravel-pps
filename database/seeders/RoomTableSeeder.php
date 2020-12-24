<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Room::create([
            'user_id' => 1,
            'room_name' => 'Pemilihan Walikota Bikini Bottom',
            'description' => 'Puja kerang ajaib..',
            'code' => 'XGHDH',
        ]);
    }
}
