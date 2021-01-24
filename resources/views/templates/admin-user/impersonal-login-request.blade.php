@php echo "<?php"
@endphp


namespace App\Http\Requests\Admin\{{ $modelWithNamespaceFromDefault }};

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ImpersonalLogin{{ $modelBaseName }} extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * {{'@'}}return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.{{ $modelDotNotation }}.impersonal-login', $this->{{ $modelVariableName }});
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * {{'@'}}return array
     */
    public function rules(): array
    {
        return [];
    }
}
