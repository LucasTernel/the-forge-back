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
}