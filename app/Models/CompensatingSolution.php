<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompensatingSolution extends Model
{
    protected $fillable = [
        'measure',
    ];

    public function vulnerabilities()
    {
        return $this->belongsToMany(Vulnerability::class, 'vulnerability_compensating_solution');
    }
}