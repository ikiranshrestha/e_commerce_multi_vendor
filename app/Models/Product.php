<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'merchant_id',
        'sku',
        'name',
        'description',
        'price',
        'stock'
    ];

    public function merchant() {
        return $this->belongsTo(Merchant::class);
    }

    public function collections() {
        return $this->belongsToMany(Collection::class)->withTimestamps();
    }
}
