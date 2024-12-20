<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'tambah-produk']);
        Permission::create(['name' => 'ubah-produk']);
        Permission::create(['name' => 'hapus-produk']);
        Permission::create(['name' => 'lihat-produk']);
        Permission::create(['name' => 'beli-produk']);
        Permission::create(['name' => 'tambah-permission']);
        Permission::create(['name' => 'tambah-role']);
        Permission::create(['name' => 'tambah-toko']);

        Role::create(['name' => 'seller']);
        Role::create(['name' => 'customer']);
        Role::create(['name' => 'admin']);

        $roleSeller = Role::findByName('seller');
        $roleSeller->givePermissionTo('tambah-produk');
        $roleSeller->givePermissionTo('ubah-produk');
        $roleSeller->givePermissionTo('hapus-produk');
        $roleSeller->givePermissionTo('lihat-produk');
        
        $roleAdmin = Role::findByName('admin');
        $roleAdmin->givePermissionTo('tambah-permission');
        $roleAdmin->givePermissionTo('tambah-role');
        $roleAdmin->givePermissionTo('tambah-toko');

        $roleCustomer = Role::findByName('customer');
        $roleCustomer->givePermissionTo('lihat-produk');
        $roleCustomer->givePermissionTo('beli-produk');
    }
}
