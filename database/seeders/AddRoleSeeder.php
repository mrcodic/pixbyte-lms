<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Schema::disableForeignKeyConstraints();
        // DB::table('roles')->truncate();

        // DB::table('permissions')->truncate();

        // DB::table('role_has_permissions')->truncate();

        // DB::table('model_has_permissions')->truncate();

        // DB::table('model_has_roles')->truncate();
        // Schema::enableForeignKeyConstraints();

        $dbTables = ['students','coupons','student_rank','setting', 'gifts', 'attendance','category','subcategory','quiz','question','question_bank'];

        $Admin = Role::first();
        foreach ($dbTables as $table){

            $readPermission   = Permission::firstOrCreate(['name' => 'read-'.$table ,   'guard_name' => 'admin', 'type' => 1]);
            $createPermission = Permission::firstOrCreate(['name' => 'create-'.$table , 'guard_name' => 'admin', 'type' => 1]);
            $updatePermission = Permission::firstOrCreate(['name' => 'update-'.$table , 'guard_name' => 'admin', 'type' => 1]);
            $deletePermission = Permission::firstOrCreate(['name' => 'delete-'.$table , 'guard_name' => 'admin', 'type' => 1]);
            /**
             * assign permission to role
             */
            $Admin ? $Admin->givePermissionTo($readPermission, $createPermission, $updatePermission, $deletePermission) :null;
        }

    }
}
