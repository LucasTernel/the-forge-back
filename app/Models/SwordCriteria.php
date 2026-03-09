<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SwordCriteria extends Model
{
    protected $table = 'sword_criterias';

    protected $fillable = ['sword_id', 'criteria_id', 'rating'];

    public function sword()
    {
        return $this->belongsTo(Sword::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class , 'criteria_id');
    }
}