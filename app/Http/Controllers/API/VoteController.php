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
            'room_id' => 'required',
            'candidate_id' => 'nullable',
            'total' => 'required',
        ]);

        if($store = Vote::create($data)) {
            Room::where('id', $store->room_id)->update(['status' => 'CLOSE']);
            return new VoteResource($store);
        }

        return response()->json([
            'error' => true,
            'message' => 'Error voting'
        ]);

    }

}
