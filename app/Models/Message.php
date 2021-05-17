<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message',
        'user_id',
        'chat_id',
    ];

    use HasFactory;

    public function chat()
    {
        $this->belongsTo(Chat::class);
    }

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
