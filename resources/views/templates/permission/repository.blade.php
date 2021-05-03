@php echo "<?php";
@endphp

namespace {{ $repoNamespace }};

use {{$modelFullName}};
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;

class {{ $repoBaseName }}
{
    private {{$modelBaseName}} $model;
    public static function init({{$modelBaseName}} $model): {{$repoBaseName}}
    {
        $instance = new self;
        $instance->model = $model;
        return $instance;
    }

    public static function store(object $data): {{$modelBaseName}}
    {
        $model = new {{$modelBaseName}}((array) $data);
        @if(in_array("slug",$columns->pluck('name')->toArray()) && in_array("name",$columns->pluck('name')->toArray()))
$model->slug = Str::slug($model->name);
        @elseif(in_array("slug",$columns->pluck('name')->toArray()) && in_array("display_name",$columns->pluck('name')->toArray()))
$model->slug = Str::slug($model->name);
        @elseif(in_array("slug",$columns->pluck('name')->toArray()) && in_array("title",$columns->pluck('name')->toArray()))
$model->slug = Str::slug($model->title);
        @endif
        // Save Relationships
        @if (count($relations))
    @if (isset($relations['belongsTo']) && count($relations['belongsTo'])){{PHP_EOL}}
        @foreach($relations["belongsTo"] as $relation)
if (isset($data->{{$relation["relationship_variable"]}})) {
            $model->{{$relation['function_name']}}()
                ->associate($data->{{$relation["relationship_variable"]}}->{{$relation['owner_key']}});
        }
        @endforeach
    @endif
        @endif{{PHP_EOL}}
        $model->saveOrFail();
        return $model;
    }

    public function show(Request $request): {{$modelBaseName}} {
        //Fetch relationships
        @if (count($relations))
@if (isset($relations['belongsTo']) && count($relations['belongsTo']))
    @php $parents = $relations['belongsTo']->pluck("function_name")->toArray(); @endphp
    $this->model->load([
    @foreach($parents as $parent)
        '{{$parent}}',
    @endforeach
    ]);
@endif
    @endif
return $this->model;
    }
    public function update(object $data): {{$modelBaseName}}
    {
        $this->model->update((array) $data);
        @if(in_array("slug",$columns->pluck('name')->toArray()) && in_array("name",$columns->pluck('name')->toArray()))
$this->model->slug = Str::slug($this->model->name);
        @elseif(in_array("slug",$columns->pluck('name')->toArray()) && in_array("display_name",$columns->pluck('name')->toArray()))
$this->model->slug = Str::slug($this->model->display_name);
        @elseif(in_array("slug",$columns->pluck('name')->toArray()) && in_array("title",$columns->pluck('name')->toArray()))
$this->model->slug = Str::slug($this->model->title);
        @endif

        // Save Relationships
        @if (count($relations))
        @if (isset($relations['belongsTo']) && count($relations['belongsTo']))
@foreach($relations["belongsTo"] as $relation){{PHP_EOL}}
        if (isset($data->{{$relation["relationship_variable"]}})) {
            $this->model->{{$relation['function_name']}}()
                ->associate($data->{{$relation["relationship_variable"]}}->{{$relation['owner_key']}});
        }
@endforeach
        @endif
        @endif{{PHP_EOL}}
        $this->model->saveOrFail();
        return $this->model;
    }

    public function destroy(): bool
    {
        return !!$this->model->delete();
    }
    public static function dtColumns() {
        $columns = [
        @foreach($columnsToQuery as $col)
@if($col ==='id')
    Column::make('{{$col}}')->title('ID')->className('all text-right'),
@elseif($col==='name'||$col==='title')
    Column::make("{{$col}}")->className('all'),
@elseif($col==='created_at'|| $col==='updated_at')
    Column::make("{{$col}}")->className('min-tv'),
@else
    Column::make("{{$col}}")->className('min-desktop-lg'),
@endif
        @endforeach
    Column::make('actions')->className('min-desktop text-right')->orderable(false)->searchable(false),
        ];
        return $columns;
    }
    public static function dt($query) {
        return DataTables::of($query)
            ->editColumn('actions', function ({{$modelBaseName}} $model) {
                $actions = '';
                if (\Auth::user()->can('view',$model)) $actions .= '<button class="bg-primary hover:bg-primary-600 p-2 px-3 focus:ring-0 focus:outline-none text-white action-button" title="View Details" data-action="show-model" data-tag="button" data-id="'.$model->id.'"><i class="fas fa-eye"></i></button>';
                if (\Auth::user()->can('update',$model)) $actions .= '<button class="bg-secondary hover:bg-secondary-600 p-2 px-3 focus:ring-0 focus:outline-none action-button" title="Edit Record" data-action="edit-model" data-tag="button" data-id="'.$model->id.'"><i class="fas fa-edit"></i></button>';
                if (\Auth::user()->can('delete',$model)) $actions .= '<button class="bg-danger hover:bg-danger-600 p-2 px-3 text-white focus:ring-0 focus:outline-none action-button" title="Delete Record" data-action="delete-model" data-tag="button" data-id="'.$model->id.'"><i class="fas fa-trash"></i></button>';
                return "<div class='gap-x-1 flex w-full justify-end'>".$actions."</div>";
            })
            ->rawColumns(['actions'])
            ->make();
    }
    public static function seedPermissions(array $perms, array $roleNames = ["administrator"],$guard=null) {
        if (!$guard) {
            $guard = config('auth.defaults.guard');
        }
        $perms = collect($perms);
        $permissions = $perms->map(function ($permission) use($guard) {
            return [
                'name' => $permission,
                'title' => Str::title(str_replace("-"," ",implode(" ",explode(".",$permission)))),
                'guard_name' => $guard,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        $roles = collect($roleNames)->map(function($role) use ($perms, $guard) {
            return [
                'name'          => $role,
                'title'         => str_replace("-"," ", Str::title($role)),
                'guard_name'    => $guard,
                'permissions'   => $perms,
            ];
        });

        $tableNames = config('permission.table_names', [
            'roles' => 'roles',
            'permissions' => 'permissions',
            'model_has_permissions' => 'model_has_permissions',
            'model_has_roles' => 'model_has_roles',
            'role_has_permissions' => 'role_has_permissions',
        ]);

        DB::transaction(function () use($tableNames, $permissions, $roles) {
            foreach ($permissions as $permission) {
                $permissionItem = DB::table($tableNames['permissions'])->where([
                    'name' => $permission['name'],
                    'guard_name' => $permission['guard_name']
                ])->first();
                if ($permissionItem === null) {
                    DB::table($tableNames['permissions'])->insert($permission);
                }
            }

            foreach ($roles as $role) {
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
}
