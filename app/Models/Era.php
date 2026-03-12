<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Era extends Model
{
    protected $fillable = ['name', 'image_cover', 'overlay', 'color'];

    public function swords()
    {
        return $this->hasMany(Sword::class);
    }

    /**
     * Accesseur image_cover :
     * - Si déjà une URL externe (http/https) → retourné tel quel
     * - Si déjà un chemin /storage/... → retourné tel quel
     * - Si valeur brute (juste le nom de fichier sans chemin) → reconstitue le chemin
     * - Si null → null
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
        $slug = Str::slug($this->name);
        return "/storage/eras/image_cover/{$slug}/{$value}";
    }
}
