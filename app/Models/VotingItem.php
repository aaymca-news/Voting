<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotingItem extends Model
{
    protected $fillable = [
        'election_id',
        'title',
        'type',
        'status',
        'voting_mode',
        'description',
    ];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function options()
    {
        return $this->hasMany(ElectionOption::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function isAnonymous(): bool
    {
        return $this->voting_mode === 'anonymous';
    }

    public function isNamed(): bool
    {
        return $this->voting_mode === 'named';
    }
}