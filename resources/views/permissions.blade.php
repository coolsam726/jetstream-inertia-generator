@php echo "<?php"
@endphp


use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class {{ $className }} extends Migration
{
    /**
     * {{'@'}}var Repository|mixed
     */
    protected $guardName;
    /**
     * {{'@'}}var array
     */
    protected $permissions;
    /**
     * {{'@'}}var array
     */
    protected $roles;

    /**
     * {{ $className }} constructor.
     */
    public function __construct()
    {
        $this->guardName = "web";

        $permissions = collect([
            '{{$titlePlural}} Module' => '{{ $modelDotNotation }}',
            'List All {{$titlePlural}}' => '{{ $modelDotNotation }}.index',
            'Create New {{$titlePlural}}' => '{{ $modelDotNotation }}.create',
            'View Any {{$titleSingular}}' => '{{ $modelDotNotation }}.show',
            'Edit or Update a Single {{$titleSingular}}' => '{{ $modelDotNotation }}.edit',
            'Delete {{$titlePlural}}' => '{{ $modelDotNotation }}.delete',
        ]);

        //Add New permissions
        $this->permissions = $permissions->map(function ($permission, $title) {
            return [
                'name' => $permission,
                'title' => $title,
                'guard_name' => $this->guardName,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        })->toArray();

        //Role should already exists
        $this->roles = [
            [
                'name' => 'administrator',
                'guard_name' => $this->guardName,
                'permissions' => $permissions,
            ],
        ];
    }

    /**
     * Run the migrations.
     *
     * {{'@'}}return void
     */
    public function up(): void
    {
        $tableNames = config('permission.table_names', [
            'roles' => 'roles',
            'permissions' => 'permissions',
            'model_has_permissions' => 'model_has_permissions',
            'model_has_roles' => 'model_has_roles',
            'role_has_permissions' => 'role_has_permissions',
        ]);
        // Add Title columns in case they don't exist.
        $roles = $tableNames['roles'];
        $permissions = $tableNames['permissions'];
        Schema::table($roles, function (Blueprint $table) use($roles) {
            $hasTitle = Schema::hasColumn($roles,"title");
            if (!$hasTitle) $table->string('title')->nullable();
        });
        Schema::table($permissions, function (Blueprint $table) use ($permissions) {
            if (!Schema::hasColumn($permissions,"title")) {
                $table->string('title')->nullable();
            }
        });
        // End add title

        DB::transaction(function () use($tableNames) {
            foreach ($this->permissions as $permission) {
                $permissionItem = DB::table($tableNames['permissions'])->where([
                    'name' => $permission['name'],
                    'guard_name' => $permission['guard_name']
                ])->first();
                if ($permissionItem === null) {
                    DB::table($tableNames['permissions'])->insert($permission);
                }
            }

            foreach ($this->roles as $role) {
                $permissions = $role['permissions'];
                unset($role['permissions']);

                $roleItem = DB::table($tableNames['roles'])->where([
                    'name' => $role['name'],
                    'guard_name' => $role['guard_name']
                ])->first();
                if ($roleItem !== null) {
                    $roleId = $roleItem->id;

                    $permissionItems = DB::table($tableNames['permissions'])->whereIn('name', $permissions)->where(
                        'guard_name',
                        $role['guard_name']
                    )->get();
                    foreach ($permissionItems as $permissionItem) {
                        $roleHasPermissionData = [
                            'permission_id' => $permissionItem->id,
                            'role_id' => $roleId
                        ];
                        $roleHasPermissionItem = DB::table($tableNames['role_has_permissions'])->where($roleHasPermissionData)->first();
                        if ($roleHasPermissionItem === null) {
                            DB::table($tableNames['role_has_permissions'])->insert($roleHasPermissionData);
                        }
                    }
                }
            }
        });
        app()['cache']->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     *
     * {{'@'}}return void
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names', [
            'roles' => 'roles',
            'permissions' => 'permissions',
            'model_has_permissions' => 'model_has_permissions',
            'model_has_roles' => 'model_has_roles',
            'role_has_permissions' => 'role_has_permissions',
        ]);
        DB::transaction(function () use ($tableNames){
            foreach ($this->permissions as $permission) {
                $permissionItem = DB::table($tableNames['permissions'])->where([
                    'name' => $permission['name'],
                    'guard_name' => $permission['guard_name']
                ])->first();
                if ($permissionItem !== null) {
                    DB::table($tableNames['permissions'])->where('id', $permissionItem->id)->delete();
                    DB::table($tableNames['model_has_permissions'])->where('permission_id', $permissionItem->id)->delete();
                }
            }
        });
        app()['cache']->forget(config('permission.cache.key'));
    }
}
