<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseModel extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = Auth::user();

            if ($user && $user->tenant_id) {
                $model->tenant_id = $user->tenant_id;
            }
        });

        static::addGlobalScope('tenant_id', function (Builder $builder) {
            if (!Auth::user()->isAdmin) {
                $builder->where('tenant_id', Auth::user()->tenant_id);
            }
        });

    }

}
