<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedAdminRoleAndUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('roles')) {
            DB::transaction(function () {
                $role = DB::table('roles')
                    ->where('name',"=", "administrator")
                    ->where("guard_name","=", "web")
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

                $user = DB::table('users')->where("email","=","admin@savannabits.com")->first();
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
                DB::table('model_has_roles')->insert([
                    "role_id" => $roleId,
                    "model_id" => $userId,
                    "model_type" => \App\Models\User::class,
                ]);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('roles')) {
            DB::transaction(function () {
                $role = DB::table('roles')
                    ->where('name',"=", "administrator")
                    ->where("guard_name","=", "web")
                    ->first();
                $userBuilder = DB::table('users')
                    ->where("email","=","admin@savannabits.com");
                $user = $userBuilder->first();
                if ($role && $user) {
                    DB::table('model_has_roles')
                        ->where("role_id","=", $role->id)
                        ->where("model_id","=", $user->id)
                        ->where("model_type","=", \App\Models\User::class)
                        ->delete();
                    ;
                    $userBuilder->delete();
                }
            });
        }
    }
}
