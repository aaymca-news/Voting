<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'description',
        'code',
    ];

    public function elections()
    {
        return $this->hasMany(Election::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_members');
    }

    public function agendas()
    {
        return $this->hasMany(MeetingAgenda::class)->latest();
    }
}