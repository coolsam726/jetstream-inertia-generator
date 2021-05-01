@php echo "<?php"
@endphp

namespace App\Http\Requests\{{ $modelWithNamespaceFromDefault }};
@php
    $standardColumns = $columns->reject(function($column) use ($relatable) {
        return in_array($column['name'], $relatable->pluck('name')->toArray())|| $column["name"]=='slug';
    });
    $relatableColumns = $columns->filter(function($column) use ($relatable) {
        return in_array($column['name'], $relatable->pluck('name')->toArray());
    })->keyBy('name');
@endphp

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use {{$modelFullName}};
class Store{{ $modelBaseName }} extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * {{'@'}}return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create',{{$modelBaseName}}::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * {{'@'}}return array
     */
    public function rules(): array
    {
        return [
            @foreach($standardColumns as $column)
@if(!($column['name'] == "updated_by_admin_user_id" || $column['name'] == "created_by_admin_user_id" ))'{{ $column['name'] }}' => [{!! implode(', ', (array) $column['serverStoreRules']) !!}],
@endif
            @endforeach
@if (count($relations))
    @if (isset($relations["belongsToMany"]) && count($relations['belongsToMany']))

            @foreach($relations['belongsToMany'] as $belongsToMany)'{{ $belongsToMany['related_table'] }}' => [{!! implode(', ', ['\'array\'']) !!}],
            @endforeach
    @endif
    @if (isset($relations["belongsTo"]) && count($relations['belongsTo']))

            @foreach($relations['belongsTo'] as $belongsTo)
'{{ $belongsTo['relationship_variable'] }}' => [{!! implode(', ', array_merge(
    ['\'array\''], collect($relatableColumns[$belongsTo['foreign_key']]['serverStoreRules'])->reject(function($rule){return str_contains($rule,'integer');})->toArray()
)) !!}],
            @endforeach
    @endif
@endif

            'assigned_roles' => ["required","array"],
        ];
    }
    /**
    * Modify input data
    *
    * {{'@'}}return array
    */
    public function sanitizedArray(): array
    {
        $sanitized = $this->validated();

        //Add your code for manipulation with request data here

        return $sanitized;
    }
    /**
    * Return modified (sanitized data) as a php object
    * @return object
    */
    public function sanitizedObject(): object {
        $sanitized = $this->sanitizedArray();
        return json_decode(collect($sanitized));
    }
}
