<?php

use App\Laravue\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Laravue\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'phone_no' => '+60129652529',
            'email' => 'admin@ebs.dev',
            'password' => Hash::make('laravue'),
        ]);
        $manager = User::create([
            'name' => 'Manager',
            'phone_no' => '+60129652528',
            'email' => 'manager@ebs.dev',
            'password' => Hash::make('laravue'),
        ]);
        $editor = User::create([
            'name' => 'Editor',
            'phone_no' => '+60129652527',
            'email' => 'editor@ebs.dev',
            'password' => Hash::make('laravue'),
        ]);
        $user = User::create([
            'name' => 'User',
            'phone_no' => '+60129652526',
            'email' => 'user@ebs.dev',
            'password' => Hash::make('laravue'),
        ]);
        $visitor = User::create([
            'name' => 'Visitor',
            'phone_no' => '+60122220959',
            'email' => 'visitor@ebs.dev',
            'password' => Hash::make('laravue'),
        ]);

        $adminRole = Role::findByName(\App\Laravue\Acl::ROLE_ADMIN);
        $managerRole = Role::findByName(\App\Laravue\Acl::ROLE_MANAGER);
        $editorRole = Role::findByName(\App\Laravue\Acl::ROLE_EDITOR);
        $userRole = Role::findByName(\App\Laravue\Acl::ROLE_USER);
        $visitorRole = Role::findByName(\App\Laravue\Acl::ROLE_VISITOR);
        $admin->syncRoles($adminRole);
        $manager->syncRoles($managerRole);
        $editor->syncRoles($editorRole);
        $user->syncRoles($userRole);
        $visitor->syncRoles($visitorRole);
        $this->call(PermissionSeeder::class);
        $this->call(BatterySeeder::class);
        $this->call(BatteryManufacturerSeeder::class);
        $this->call(BatteryModelSeeder::class);
        $this->call(BatterySeriesSeeder::class);
        $this->call(BatteryTypeSeeder::class);
        $this->call(BatteryTradeInSeeder::class);
        $this->call(BatterySpecificationSeeder::class);
        $this->call(CarSeeder::class);
        $this->call(CarManufacturerSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(TransactionRecordSeeder::class);
        $this->call(CarsTableSeeder::class);
        $this->call(TransactionRecordsTableSeeder::class);
    }
}
