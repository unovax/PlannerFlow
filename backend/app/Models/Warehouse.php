<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends BaseModel
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'address', 'city', 'state', 'country', 'tenant_id'];
}
