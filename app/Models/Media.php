<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasFactory;
    protected $fillable = ['url', 'sword_id', 'type'];

    public function sword(): BelongsTo
    {
        return $this->belongsTo(Sword::class);
    }

    public function getUrlAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http') && !str_starts_with($value, '/storage')) {
            $sword = $this->sword;
            if ($sword) {
                return "/storage/{$sword->collection_id}/{$this->sword_id}/" . $value;
            }
        }
        return $value;
    }
}