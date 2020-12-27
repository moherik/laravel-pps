<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CandidateResource;
use App\Http\Resources\RoomResource;
use App\Models\Candidate;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{

    public function join($code)
    {
        return new RoomResource(Room::where('code', $code)->firstOrFail());
    }

    public function voteData($code)
    {
        $room = Room::where('code', $code)->firstOrFail();

        return response()->json([
            'valid' => $room->validVotes(),
            'invalid' => $room->invalidVotes(),
            'total' => $room->totalVotes()
        ]);
    }

    public function candidates($roomId)
    {
        return CandidateResource::collection(Candidate::where('room_id', $roomId)->orderBy('order', 'ASC')->get());
    }

}
