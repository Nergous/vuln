<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name',
        'date',
        'number',
        'stamp_high_date',
        'stamp_high_number',
        'stamp_low_date',
        'stamp_low_number',
        'status',
        'path_to_file',
    ];

    protected $casts = [
        'date' => 'date',
        'stamp_high_date' => 'date',
        'stamp_low_date' => 'date',
    ];

    public function vulnerabilities()
    {
        return $this->belongsToMany(Vulnerability::class, 'document_vulnerability');
    }
    public function delayedDocument()
    {
        return $this->hasOne(DelayedDocument::class, 'document_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tags::class);
    }
}
