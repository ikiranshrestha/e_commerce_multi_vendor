<?php

// app/Models/Collection.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model {
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'merchant_id',
        'name',
        'description'];

    public function merchant() {
        return $this->belongsTo(Merchant::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
