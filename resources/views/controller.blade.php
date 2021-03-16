@php echo "<?php";
@endphp

namespace {{ $controllerNamespace }};
@if($export)
    use App\Exports\{{$exportBaseName}};
    use Maatwebsite\Excel\Excel
@endif
use App\Http\Controllers\Controller;
use App\Http\Requests\{{ $modelWithNamespaceFromDefault }}\Index{{ $modelBaseName }};
use App\Http\Requests\{{ $modelWithNamespaceFromDefault }}\Store{{ $modelBaseName }};
use App\Http\Requests\{{ $modelWithNamespaceFromDefault }}\Update{{ $modelBaseName }};
use App\Http\Requests\{{ $modelWithNamespaceFromDefault }}\Destroy{{ $modelBaseName }};
use {{$modelFullName}};
use {{$repoFullName}};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Yajra\DataTables\Html\Column;

class {{ $controllerBaseName }}  extends Controller
{
    private {{$repoBaseName}} $repo;
    public function __construct({{$repoBaseName}} $repo)
    {
        $this->repo = $repo;
    }

    /**
    * Display a listing of the resource.
    *
    * @param Request $request
    * @return  \Inertia\Response
    * @throws \Illuminate\Auth\Access\AuthorizationException
    */
    public function index(Request $request): \Inertia\Response
    {
        $this->authorize('viewAny', {{$modelBaseName}}::class);

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
        return Inertia::render('{{$modelPlural}}/Index',[
            "can" => [
                "viewAny" => \Auth::user()->can('viewAny', {{$modelBaseName}}::class),
                "create" => \Auth::user()->can('create', {{$modelBaseName}}::class),
            ],
            "columns" => $columns,
        ]);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Inertia\Response
    */
    public function create()
    {
        $this->authorize('create', {{$modelBaseName}}::class);
        return Inertia::render("{{$modelPlural}}/Create",[
            "can" => [
            "viewAny" => \Auth::user()->can('viewAny', {{$modelBaseName}}::class),
            "create" => \Auth::user()->can('create', {{$modelBaseName}}::class),
            ]
        ]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * {{"@"}}param Store{{$modelBaseName}} $request
    * {{"@"}}return \Illuminate\Http\RedirectResponse
    */
    public function store(Store{{$modelBaseName}} $request)
    {
        try {
            $data = $request->sanitizedObject();
            ${{$modelVariableName}} = $this->repo::store($data);
            return \Redirect::route('admin.{{$modelRouteAndViewName}}.index')->with(['success' => "The {{$modelBaseName}} was created succesfully."]);
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return back()->with([
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
    * Display the specified resource.
    *
    * {{"@"}}param Request $request
    * {{"@"}}param {{$modelBaseName}} ${{$modelVariableName}}
    * {{"@"}}return \Inertia\Response|\Illuminate\Http\RedirectResponse
    */
    public function show(Request $request, {{$modelBaseName}} ${{$modelVariableName}})
    {
        try {
            $this->authorize('view', ${{$modelVariableName}});
            //Fetch relationships
            @if (count($relations)){{PHP_EOL}}
                @if (isset($relations['belongsTo']) && count($relations['belongsTo'])){{PHP_EOL}}
                    @php $parents = $relations['belongsTo']->pluck("function_name")->toArray(); @endphp
                    ${{$modelVariableName}}->load([

                    @foreach($parents as $parent)
                        '{{$parent}}',
                    @endforeach

                    ]);
                @endif
            @endif
            return Inertia::render("{{$modelPlural}}/Show", ["model" => ${{$modelVariableName}}]);
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return back()->with([
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
    * Show Edit Form for the specified resource.
    *
    * {{"@"}}param Request $request
    * {{"@"}}param {{$modelBaseName}} ${{$modelVariableName}}
    * {{"@"}}return \Inertia\Response|\Illuminate\Http\RedirectResponse
    */
    public function edit(Request $request, {{$modelBaseName}} ${{$modelVariableName}})
    {
        try {
            $this->authorize('update', ${{$modelVariableName}});
            //Fetch relationships
            @if (count($relations)){{ PHP_EOL }}
                @if (isset($relations['belongsTo']) && count($relations['belongsTo'])){{PHP_EOL}}
                    @php $parents = $relations['belongsTo']->pluck("function_name")->toArray(); @endphp
                    ${{$modelVariableName}}->load([

                    @foreach($parents as $parent)
                        '{{$parent}}',
                    @endforeach

                    ]);
                @endif
            @endif
            return Inertia::render("{{$modelPlural}}/Edit", ["model" => ${{$modelVariableName}}]);
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return back()->with([
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * {{"@"}}param Update{{$modelBaseName}} $request
    * {{"@"}}param {$modelBaseName} ${{$modelVariableName}}
    * {{"@"}}return \Illuminate\Http\RedirectResponse
    */
    public function update(Update{{$modelBaseName}} $request, {{$modelBaseName}} ${{$modelVariableName}})
    {
        try {
            $data = $request->sanitizedObject();
            $res = $this->repo::init(${{$modelVariableName}})->update($data);
            return \Redirect::route('admin.{{$modelRouteAndViewName}}.index')->with(['success' => "The {{$modelBaseName}} was updated succesfully."]);
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return back()->with([
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * {{"@"}}param {{$modelBaseName}} ${{$modelVariableName}}
    * {{"@"}}return \Illuminate\Http\RedirectResponse
    */
    public function destroy(Destroy{{$modelBaseName}} $request, {{$modelBaseName}} ${{$modelVariableName}})
    {
        $res = $this->repo::init(${{$modelVariableName}})->destroy();
        if ($res) {
            return \Redirect::route('admin.{{$modelRouteAndViewName}}.index')->with(['success' => "The {{$modelBaseName}} was deleted succesfully."]);
        }
        else {
            return \Redirect::route('admin.{{$modelRouteAndViewName}}.index')->with(['error' => "The {{$modelBaseName}} could not be deleted."]);
        }
    }
}
