<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = [
        'name',
        'active',
        'description',
        'is_private',
        'created_by'
    ];



    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');




    }

public function messages()
{
    return $this->hasMany(Message::class);
}


}

