<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealSolution extends Model
{
    protected $fillable = [
        'solution',
        'path_to_file'
    ];

    public function vulnerabilities()
    {
        return $this->belongsToMany(Vulnerability::class, 'vulnerability_real_solution');
    }
}