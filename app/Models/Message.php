<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'channel_id',
        'user_id',
        'content',
        'reference_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}