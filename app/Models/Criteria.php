<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $fillable = ['name', 'short_description'];

    public function swords()
    {
        return $this->belongsToMany(Sword::class , 'sword_criterias')->withPivot('rating')->withTimestamps();
    }
}