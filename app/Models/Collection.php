<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = ['name', 'user_id', 'image_cover'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function swords()
    {
        return $this->hasMany(Sword::class);
    }
}