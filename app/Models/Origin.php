<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Origin extends Model
{
    protected $fillable = ['name', 'image_cover', 'overlay', 'color'];

    public function swords()
    {
        return $this->hasMany(Sword::class);
    }
}