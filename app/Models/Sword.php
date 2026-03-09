<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sword extends Model
{
    protected $fillable = [
        'name',
        'description',
        'short_description',
        'image_cover',
        'type_id',
        'origin_id',
        'collection_id',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function origin()
    {
        return $this->belongsTo(Origin::class);
    }

    public function criteria()
    {
        return $this->belongsToMany(Criteria::class , 'sword_criterias')->withPivot('rating')->withTimestamps();
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}