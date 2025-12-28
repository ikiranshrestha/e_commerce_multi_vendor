<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $fillable = [
        'merchant_id',
        'file_path',
        'status',
        'total_rows',
        'processed_rows',
        'failed_rows',
    ];

    protected $appends = ['progress'];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function getProgressAttribute(): int
    {
        if ($this->total_rows === 0) {
            return 0;
        }

        return (int) round(
            ($this->processed_rows + $this->failed_rows) / $this->total_rows * 100
        );
    }
}
