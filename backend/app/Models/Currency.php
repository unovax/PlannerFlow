<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends BaseModel
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'exchange_rate', 'symbol', 'tenant_id'];
}
