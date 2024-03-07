<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'create_users',
                'display_name' => 'Crear usuarios',
            ],
            [
                'name' => 'read_users',
                'display_name' => 'Leer usuarios',
            ],
            [
                'name' => 'update_users',
                'display_name' => 'Actualizar usuarios',
            ],
            [
                'name' => 'delete_users',
                'display_name' => 'Eliminar usuarios',
            ],
            [
                'name' => 'create_roles',
                'display_name' => 'Crear roles',
            ],
            [
                'name' => 'read_roles',
                'display_name' => 'Leer roles',
            ],
            [
                'name' => 'update_roles',
                'display_name' => 'Actualizar roles',
            ],
            [
                'name' => 'delete_roles',
                'display_name' => 'Eliminar roles',
            ],
            [
                'name' => 'create_currencies',
                'display_name' => 'Crear monedas',
            ],
            [
                'name' => 'read_currencies',
                'display_name' => 'Leer monedas',
            ],
            [
                'name' => 'update_currencies',
                'display_name' => 'Actualizar monedas',
            ],
            [
                'name' => 'delete_currencies',
                'display_name' => 'Eliminar monedas',
            ],
            [
                'name' => 'create_warehouses',
                'display_name' => 'Crear almacenes',
            ],
            [
                'name' => 'read_warehouses',
                'display_name' => 'Leer almacenes',
            ],
            [
                'name' => 'update_warehouses',
                'display_name' => 'Actualizar almacenes',
            ],
            [
                'name' => 'delete_warehouses',
                'display_name' => 'Eliminar almacenes',
            ],
            [
                'name' => 'create_clients',
                'display_name' => 'Crear clientes',
            ],
            [
                'name' => 'read_clients',
                'display_name' => 'Leer clientes',
            ],
            [
                'name' => 'update_clients',
                'display_name' => 'Actualizar clientes',
            ],
            [
                'name' => 'delete_clients',
                'display_name' => 'Eliminar clientes',
            ],
            [
                'name' => 'create_suppliers',
                'display_name' => 'Crear proveedores',
            ],
            [
                'name' => 'read_suppliers',
                'display_name' => 'Leer proveedores',
            ],
            [
                'name' => 'update_suppliers',
                'display_name' => 'Actualizar proveedores',
            ],
            [
                'name' => 'delete_suppliers',
                'display_name' => 'Eliminar proveedores',
            ],
            [
                'name' => 'create_products',
                'display_name' => 'Crear productos',
            ],
            [
                'name' => 'read_products',
                'display_name' => 'Leer productos',
            ],
            [
                'name' => 'update_products',
                'display_name' => 'Actualizar productos',
            ],
            [
                'name' => 'delete_products',
                'display_name' => 'Eliminar productos',
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
