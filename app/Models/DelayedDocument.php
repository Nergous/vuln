<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DelayedDocument extends Model
{
    use HasFactory;
    protected $table = 'delayed_documents';

    protected $fillable = ['document_id', 'delayed_date', 'reason'];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
