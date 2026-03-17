<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['user_id', 'sword_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sword()
    {
        return $this->belongsTo(Sword::class);
    }
}