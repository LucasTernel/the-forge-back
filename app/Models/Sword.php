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
        'era_id',
        'origin_id',
        'collection_id',
    ];

    public function era()
    {
        return $this->belongsTo(Era::class);
    }

    public function origin()
    {
        return $this->belongsTo(Origin::class);
    }

    public function criteria()
    {
        return $this->belongsToMany(Criteria::class, 'sword_criterias')->withPivot('rating')->withTimestamps();
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Accesseur image_cover :
     * - URL externe (http/https)  → retourné tel quel
     * - Chemin /storage/...       → retourné tel quel
     * - Valeur brute (nom fichier) → reconstitue /storage/{collection_id}/{id}/{filename}
     * - null                      → null
     */
    public function getImageCoverAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }

        if (str_starts_with($value, 'http') || str_starts_with($value, '/storage')) {
            return $value;
        }

        // Valeur brute → reconstitue le chemin
        return "/storage/{$this->collection_id}/{$this->id}/{$value}";
    }
}