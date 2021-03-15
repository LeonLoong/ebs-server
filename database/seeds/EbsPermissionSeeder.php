<?php

use Illuminate\Database\Seeder;

class EbsPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        {
            \App\Laravue\Models\Permission::findOrCreate('create ebs', 'api');
            \App\Laravue\Models\Permission::findOrCreate('read ebs', 'api');
            \App\Laravue\Models\Permission::findOrCreate('update ebs', 'api');
            \App\Laravue\Models\Permission::findOrCreate('delete ebs', 'api');       
        
            // Assign new permissions to admin group
            $adminRole = App\Laravue\Models\Role::findByName(App\Laravue\Acl::ROLE_ADMIN);
            $adminRole->givePermissionTo(['create ebs', 'read ebs', 'update ebs', 'delete ebs']);
        }
    }
}
