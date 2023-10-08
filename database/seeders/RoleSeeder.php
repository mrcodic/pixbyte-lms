<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();

        DB::table('permissions')->truncate();

        DB::table('role_has_permissions')->truncate();

        DB::table('model_has_permissions')->truncate();

        DB::table('model_has_roles')->truncate();
        Schema::enableForeignKeyConstraints();

        $superRole = Role::updateOrCreate(['name' => 'Admin','guard_name' => 'admin','type'=>1]);
        $admin = Admin::where('email','super_admin@app.com')->first();
        if(!$admin){
            // $admin = Admin::updateOrCreate([ 'name'=>'super', 'email'=>'super_admin@app.com'],[
            $admin = DB::table('admins')->insert([
                'name'=>'super',
                'email'     =>'super_admin@app.com',
                'password'  =>bcrypt('123456'),
                'phone'     =>"01066273085"
            ]);

            $admin = Admin::where('email','super_admin@app.com')->first();
        }

        $dbTables = adminDbTablesPermissions();
        foreach ($dbTables as $table){
                // admin permission
                Permission::create(['name' => 'read-'.$table  ,'guard_name' => 'admin','type'=>1])->assignRole($superRole);
                Permission::create(['name' => 'create-'.$table,'guard_name' => 'admin','type'=>1])->assignRole($superRole);
                Permission::create(['name' => 'update-'.$table,'guard_name' => 'admin','type'=>1])->assignRole($superRole);
                Permission::create(['name' => 'delete-'.$table,'guard_name' => 'admin','type'=>1])->assignRole($superRole);
            /**
             * assign permission to role
             */
                // $superRole->givePermissionTo($readPermission,$createPermission, $updatePermission, $deletePermission);
        }

        if($superRole)
            $admin->syncRoles('superAdmin');

        $superInstructorRole = Role::updateOrCreate(['name' => 'Instructor','guard_name' => 'web','type'=>2]);

        $dbTables =instructorDbTablesPermissions();
        foreach ($dbTables as $table){
            // permission
            Permission::create(['name' => 'read-'.$table  ,'guard_name' => 'web','type'=>2])->assignRole($superInstructorRole);
            Permission::create(['name' => 'create-'.$table,'guard_name' => 'web','type'=>2])->assignRole($superInstructorRole);
            Permission::create(['name' => 'update-'.$table,'guard_name' => 'web','type'=>2])->assignRole($superInstructorRole);
            Permission::create(['name' => 'delete-'.$table,'guard_name' => 'web','type'=>2])->assignRole($superInstructorRole);

            // $superInstructorRole->givePermissionTo($readPermission,$createPermission, $updatePermission, $deletePermission);
        }

        $superStudentOnlineRole = Role::updateOrCreate(['name' => 'superStudentOnline','guard_name' => 'web','type'=>3]);

        // $studentTables= studentDbTablesPermissions();
        $dbTables =studentOnlineDbTablesPermissions();
        foreach ($dbTables as $table){
            // if (! in_array($table ,$studentTables)): v
            // s permission
                Permission::create(['name' => 'read-'.$table.'-online'  ,'guard_name' => 'web','type'=>3])->assignRole($superStudentOnlineRole);
                Permission::create(['name' => 'create-'.$table.'-online','guard_name' => 'web','type'=>3])->assignRole($superStudentOnlineRole);
                Permission::create(['name' => 'update-'.$table.'-online','guard_name' => 'web','type'=>3])->assignRole($superStudentOnlineRole);
                Permission::create(['name' => 'delete-'.$table.'-online','guard_name' => 'web','type'=>3])->assignRole($superStudentOnlineRole);
            // endif;
            // $superStudentRole->givePermissionTo($readPermission,$createPermission, $updatePermission, $deletePermission);
        }

        $superStudentOfflineRole = Role::updateOrCreate(['name' => 'superStudentOffline','guard_name' => 'web','type'=>4]);

        $dbTables =studentOfflineDbTablesPermissions();
        foreach ($dbTables as $table){
            // // s permission
            // if (! in_array($table ,$studentTables)):
                Permission::create(['name' => 'read-'.$table.'-offline'  ,'guard_name' => 'web','type'=>4])->assignRole($superStudentOfflineRole);
                Permission::create(['name' => 'create-'.$table.'-offline','guard_name' => 'web','type'=>4])->assignRole($superStudentOfflineRole);
                Permission::create(['name' => 'update-'.$table.'-offline','guard_name' => 'web','type'=>4])->assignRole($superStudentOfflineRole);
                Permission::create(['name' => 'delete-'.$table.'-offline','guard_name' => 'web','type'=>4])->assignRole($superStudentOfflineRole);
            // endif;
            // $superStudentRole->givePermissionTo($readPermission,$createPermission, $updatePermission, $deletePermission);
        }
    }
}
