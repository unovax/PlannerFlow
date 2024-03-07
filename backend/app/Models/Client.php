<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'id',
        'code',
        'name',
        'rfc',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'currency_id',
        'tenant_id'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

}
