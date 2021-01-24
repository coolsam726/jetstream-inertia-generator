@php echo "<?php"
@endphp


namespace App\Http\Requests\Api\{{ $modelWithNamespaceFromDefault }};

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class Index{{ $modelBaseName }} extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * {{'@'}}return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('{{ str_plural($modelDotNotation) }}.index');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * {{'@'}}return array
     */
    public function rules(): array
    {
        return [
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
        ];
    }
}
