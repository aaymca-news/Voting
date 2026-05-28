<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    protected $fillable = [
        'group_id',
        'title',
        'description',
        'status',
        'election_type',
        'starts_at',
        'ends_at',
        'is_active',
        'show_results_after_end',
    ];

    public function isPositional(): bool
    {
        return $this->election_type === 'positional';
    }

    public function isMotion(): bool
    {
        return $this->election_type === 'motion';
    }

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'show_results_after_end' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function options()
{
    return $this->hasMany(ElectionOption::class);
}

public function votingItems()
{
    return $this->hasMany(VotingItem::class);
}

}