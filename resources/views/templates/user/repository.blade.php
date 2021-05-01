@php echo "<?php";
@endphp

namespace {{ $repoNamespace }};

use {{$modelFullName}};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use \Spatie\Permission\Models\Role;

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
        if (isset($password) && $data->password) {
            $model->password = \Hash::make($data->password);
        }
        $model->saveOrFail();
        $assignedRoles = collect($data->assigned_roles)->where("checked", true)->pluck('name');
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
        if (isset($data->password) && $data->password) {
            $this->model->password = \Hash::make($data->password);
        }
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
    public function assignRole(array $data): bool {
        $role = Role::whereName($data['name'])->firstOrFail();
        if ($data['checked']) {
            $this->model->assignRole([$role]);
        } else  {
            if ($this->model->hasRole($role->name)) {
                $this->model->removeRole($role);
            }
        }
        return $this->model->hasRole($role->name);
    }
}
