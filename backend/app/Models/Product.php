<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'cost',
        'price',
        'stock',
        'image',
        'category_id'
    ];
}
