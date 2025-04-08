<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(CategoryTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(CreateAdminUserSeeder::class);
        $this->call(EstadosTableSeeder::class);
        $this->call(ProductsSeeder::class);
        $this->call(RoleAddressSeeder::class);
        $this->call(ShippingAddressSeeder::class);

    }
}
