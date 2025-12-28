<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $fillable = [
        'merchant_id',
        'file_path',
        'status',
        'processed_rows',
        'failed_rows',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
