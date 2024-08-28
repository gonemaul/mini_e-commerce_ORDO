<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $super_admin =User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'support@mail.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
        User::factory()->create([
            'name' => 'maul',
            'email' => 'maul@mail.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
        User::factory()->create([
            'name' => 'user api',
            'email' => 'api@mail.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $permissions = [
            ['name' => 'user_view'],
            [ 'name' => 'user_export' ],
            [ 'name' => 'assign_roles' ],
            [ 'name' => 'category_view' ],
            [ 'name' => 'category_create' ],
            [ 'name' => 'category_edit' ],
            [ 'name' => 'category_delete' ],
            [ 'name' => 'category_exim' ],
            [ 'name' => 'product_view' ],
            [ 'name' => 'product_create' ],
            [ 'name' => 'product_edit' ],
            [ 'name' => 'product_delete' ],
            [ 'name' => 'product_exim' ],
            [ 'name' => 'order_view' ],
            [ 'name' => 'order_view_detail' ],
            [ 'name' => 'order_export' ],
            [ 'name' => 'order_update' ],
        ];
        $Role_SA = Role::create(['name' => 'Super Admin']);
        foreach($permissions as $permission){
            Permission::create($permission);
            $Role_SA->givePermissionTo($permission);
        }
        $super_admin->assignRole('Super Admin');
        // User::factory(10)->create();
        Category::factory(10)->create();
        Product::factory(10)->create();
        Order::factory(10)->create();
    }
}