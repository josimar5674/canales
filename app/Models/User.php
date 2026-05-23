<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{

    protected $fillable = [
    'name',
    'email',
    'password',
    'username',
    'photo',
    'role',
    'active'
];

protected $hidden = [
    'password',
    'remember_token',
];
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];


    }
public function messages()
{
    return $this->hasMany(Message::class);
}

public function channels()
{
    return $this->belongsToMany(Channel::class);
}

}
