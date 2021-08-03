<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SeedAdminRoleAndUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = DB::table('users')->where("email", "=", "admin@savannabits.com")->first();
        if (Schema::hasTable('roles')) {
            DB::transaction(function () use ($user) {
                $role = DB::table('roles')
                    ->where('name', "=", "administrator")
                    ->where("guard_name", "=", "web")
                    ->first();
                if (!$role) {
                    $roleId = DB::table('roles')->insertGetId([
                        "name" => "administrator",
                        "guard_name" => "web",
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]);
                } else {
                    $roleId  = $role->id;
                }

                $user = DB::table('users')->where("email", "=", "admin@savannabits.com")->first();
                if (!$user) {
                    $userId = DB::table('users')->insertGetId([
                        "name" => "Administrator",
                        "email" => "admin@savannabits.com",
                        "password" => Hash::make("password"),
                        "created_at" => now(),
                        "updated_at" => now(),
                        "email_verified_at" => now(),
                    ]);
                } else {
                    $userId = $user->id;
                }
                $exists = DB::table("model_has_roles")->where("role_id","=", $roleId)->where("model_id","=", $userId)->where("model_type","=",\App\MOdels\User::class)->exists();
                if (!$exists) {
                    DB::table('model_has_roles')->insert([
                        "role_id" => $roleId,
                        "model_id" => $userId,
                        "model_type" => \App\Models\User::class,
                    ]);
                }
            });
        } else {
            abort(500, "The roles table does not exist. Ensure you run the permissions migration before running this seeder.");
        }
    }
}
