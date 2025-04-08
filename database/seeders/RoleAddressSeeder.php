<?php

namespace Database\Seeders;

use App\Models\RoleAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAddress = [
            ['name' => 'Centro de distribución'],
            ['name' => 'Direccion personal'],
            ['name' => 'Oficina'],
            ['name' => 'Almacen'],
            ['name' => 'Tienda'],
            ['name' => 'Other'],
        ];
        foreach ($roleAddress as $role) {
            RoleAddress::create($role);
        }
    }
}
