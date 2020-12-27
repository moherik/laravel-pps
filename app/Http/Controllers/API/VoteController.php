<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\VoteResource;
use App\Models\Room;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{

    public function vote(Request $request)
    {
        $data = $this->validate($request, [
            '*.room_id' => 'required',
            '*.candidate_id' => 'nullable',
            '*.total' => 'required',
        ]);

        if($store = Vote::insert($data)) {
            Room::where('id', $data[0]["room_id"])->update(['status' => 'CLOSE']);
            return response()->json([
                'status' => 'OK',
                'message' => 'Success voting'
            ], 200);
        }

        return response()->json([
            'status' => 'ERROR',
            'message' => 'Error voting'
        ], 500);

    }

}
