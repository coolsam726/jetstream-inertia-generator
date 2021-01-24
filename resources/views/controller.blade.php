@php echo "<?php";
@endphp

namespace {{ $controllerNamespace }};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
class {{ $controllerBaseName }} extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('{{str_plural($modelRouteAndViewName)}}.index');
        $columns = [
            @foreach($columnsToQuery as $col)
[
                "data" => "{{$col}}",
                "name" => "{{$col}}",
                "title" => "{{ str_replace("_", " ", Str::title($col)) }}"
            ],
            @endforeach
];

        return view('backend.{{str_plural($modelRouteAndViewName)}}.index', compact('columns'));
    }
@if($export)

    /**
     * Export entities
     *
     * {{'@'}}return BinaryFileResponse|null
     */
    public function export(): ?BinaryFileResponse
    {
        return Excel::download(app({{ $exportBaseName }}::class), '{{ str_plural($modelVariableName) }}.xlsx');
    }
@endif}
