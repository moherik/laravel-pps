<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = ['room_id','name','image','order'];

    public function votes()
    {
        return $this->hasMany(Vote::class, 'candidate_id', 'id');
    }

    public function totalValidVotes()
    {
        return $this->hasMany(Vote::class, 'room_id', 'room_id')->where('votes.candidate_id', '!=', null)->sum('total');
    }

    public function percentage()
    {
        $vote = $this->votes()->sum('total');
        $total = $this->totalValidVotes();

        if($vote != null && $total != null) {
            return round($vote/$total*100, 2);
        }

        return round(0, 2);
    }

}
