<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'room_name', 'description', 'code', 'status'];

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function totalVotes()
    {
        return $this->hasMany(Vote::class, 'room_id', 'id')->sum('total');
    }

    public function validVotes()
    {
        return $this->hasMany(Vote::class, 'room_id', 'id')->where('votes.candidate_id', '!=', null)->sum('total');
    }

    public function invalidVotes()
    {
        return $this->hasMany(Vote::class, 'room_id', 'id')->where('votes.candidate_id', null)->sum('total');
    }
}
