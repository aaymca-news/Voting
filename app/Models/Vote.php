<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'user_id',
        'election_id',
        'voting_item_id',
        'election_option_id',
    ];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function votingItem()
    {
        return $this->belongsTo(VotingItem::class);
    }

    public function electionOption()
    {
        return $this->belongsTo(ElectionOption::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}