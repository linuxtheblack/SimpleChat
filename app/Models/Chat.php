<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'uuid'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Check if user is a part of the chat
     *
     * @param int $userId
     *
     * @return bool
     */
    public function userHasAccess(int $userId): bool
    {
        return (bool) $this->users->find($userId);
    }
}
