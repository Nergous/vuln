<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealSolution extends Model
{
    protected $fillable = [
        'solution',
    ];

    public function vulnerabilities()
    {
        return $this->belongsToMany(Vulnerability::class, 'vulnerability_real_solution');
    }
}