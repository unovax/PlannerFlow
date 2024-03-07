<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
//import faker
use Faker\Factory as Faker;
class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        Tenant::create([
            'name' => $faker->name,
            'phone' => $faker->phoneNumber,
            'country' => $faker->country,
            'state' => $faker->state,
            'city' => $faker->city,
            'address' => $faker->address
        ]);

        $user = User::create([
            'name' => $faker->name,
            'email' => "unova.x98@gmail.com",
            'password' => bcrypt('admin123'),
            'tenant_id' => 1
        ]);

        $role = Role::create([
            'code' => '001',
            'name' => 'Admin',
            'description' => 'Administrator',
            'tenant_id' => 1
        ]);

        $role->asyncPermissions(Permission::all());

        $user->asyncRoles($role);
    }
}
