@php echo "<?php"
@endphp


namespace App\Http\Requests\Admin\{{ $modelWithNamespaceFromDefault }};
@php
    if($translatable->count() > 0) {
        $translatableColumns = $columns->filter(function($column) use ($translatable) {
            return in_array($column['name'], $translatable->toArray());
        });
        $standardColumn = $columns->reject(function($column) use ($translatable) {
            return in_array($column['name'], $translatable->toArray());
        });
    }
@endphp

@if($translatable->count() > 0)use Savannabits\AdminUI\TranslatableFormRequest;
@else
use Illuminate\Foundation\Http\FormRequest;
@endif
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

@if($translatable->count() > 0)class Store{{ $modelBaseName }} extends TranslatableFormRequest
@else
class Store{{ $modelBaseName }} extends FormRequest
@endif
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * {{'@'}}return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.{{ $modelDotNotation }}.create');
    }

@if($translatable->count() > 0)/**
     * Get the validation rules that apply to the requests untranslatable fields.
     *
     * {{'@'}}return array
     */
    public function untranslatableRules(): array {
        return [
            @foreach($standardColumn as $column)'{{ $column['name'] }}' => [{!! implode(', ', (array) $column['serverStoreRules']) !!}],
            @endforeach
@if (count($relations))
    @if (count($relations['belongsToMany']))

            @foreach($relations['belongsToMany'] as $belongsToMany)'{{ $belongsToMany['related_table'] }}' => [{!! implode(', ', ['\'array\'']) !!}],
            @endforeach
    @endif
@endif

        ];
    }

    /**
     * Get the validation rules that apply to the requests translatable fields.
     *
     * {{'@'}}return array
     */
    public function translatableRules($locale): array {
        return [
            @foreach($translatableColumns as $column)'{{ $column['name'] }}' => [{!! implode(', ', (array) $column['serverStoreRules']) !!}],
            @endforeach

        ];
    }
@else
    /**
     * Get the validation rules that apply to the request.
     *
     * {{'@'}}return array
     */
    public function rules(): array
    {
@php
    $columns = collect($columns)->reject(function($column) {
        return $column['name'] == 'activated';
    })->toArray();
@endphp
        $rules = [
            @foreach($columns as $column)'{{ $column['name'] }}' => [{!! implode(', ', (array) $column['serverStoreRules']) !!}],
            @endforeach
@if (count($relations))
    @if (count($relations['belongsToMany']))

            @foreach($relations['belongsToMany'] as $belongsToMany)'{{ $belongsToMany['related_table'] }}' => [{!! implode(', ', ['\'array\'']) !!}],
            @endforeach
    @endif
@endif

        ];

        if (Config::get('admin-auth.activation_enabled')) {
            $rules['activated'] = ['required', 'boolean'];
        }

        return $rules;
    }
@endif

    /**
     * Modify input data
     *
     * {{'@'}}return array
     */
    public function getModifiedData(): array
    {
        $data = $this->only(collect($this->rules())->keys()->all());
        if (!Config::get('admin-auth.activation_enabled')) {
            $data['activated'] = true;
        }
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $data;
    }
}
