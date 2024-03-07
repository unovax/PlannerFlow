<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends BaseModel
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'description', 'tenant_id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function assignPermission(Permission $permission)
    {
        return $this->permissions()->save($permission);
    }

    public function asyncPermissions($permissions)
    {
        return $this->permissions()->sync($permissions);
    }

    public function removePermission(Permission $permission)
    {
        return $this->permissions()->detach($permission);
    }

    public function hasPermission(Permission $permission)
    {
        return $this->permissions->contains($permission);
    }

    public function assignUser(User $user)
    {
        return $this->users()->save($user);
    }

    public function removeUser(User $user)
    {
        return $this->users()->detach($user);
    }

}
