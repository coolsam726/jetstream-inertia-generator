@php echo "<?php";
@endphp

namespace {{ $repoNamespace }};

use {{$modelFullName}};
use Illuminate\Support\Str;

class {{ $repoBaseName }}
{
    private {{$modelBaseName}} $model;
    public static function init({{$modelBaseName}} $model)
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
}
