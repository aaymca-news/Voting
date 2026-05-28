<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingAgenda extends Model
{
    protected $fillable = [
        'group_id',
        'uploaded_by',
        'title',
        'file_path',
        'original_filename',
        'file_type',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
