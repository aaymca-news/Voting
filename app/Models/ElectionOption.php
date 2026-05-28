<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectionOption extends Model
{
    protected $fillable = [
        'election_id',
        'voting_item_id',
        'name',
        'description',
        'photo_path',
    ];

    public function photoUrl(): ?string
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function votingItem()
    {
        return $this->belongsTo(VotingItem::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}