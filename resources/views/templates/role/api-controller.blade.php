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
use Savannabits\JetstreamInertiaGenerator\Helpers\ApiResponse;
use Savannabits\Pagetables\Column;
use Savannabits\Pagetables\Pagetables;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class {{ $controllerBaseName }}  extends Controller
{
    private ApiResponse $api;
    private {{$repoBaseName}} $repo;
    public function __construct(ApiResponse $apiResponse, {{$repoBaseName}} $repo)
    {
        $this->api = $apiResponse;
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource (paginated).
     * {{"@"}}return \Illuminate\Http\JsonResponse
     */
    public function index(Index{{ $modelBaseName }} $request)
    {
        $query = {{$modelBaseName}}::query(); // You can extend this however you want.
        $cols = [
            @foreach($columnsToQuery as $col)Column::name('{{$col}}')->title('{{str_replace('_',' ',Str::title($col))}}')->sort()->searchable(),
            @endforeach

            Column::name('actions')->title('')->raw()
        ];
        $data = Pagetables::of($query)->columns($cols)->make(true);
        return $this->api->success()->message("List of {{$modelPlural}}")->payload($data)->send();
    }

    public function dt(Request $request) {
        $query = {{$modelBaseName}}::query()->select({{$modelBaseName}}::getModel()->getTable().'.*'); // You can extend this however you want.
        return $this->repo::dt($query);
    }
    /**
     * Store a newly created resource in storage.
     *
     * {{"@"}}param Store{{$modelBaseName}} $request
     * {{"@"}}return \Illuminate\Http\JsonResponse
     */
    public function store(Store{{$modelBaseName}} $request)
    {
        try {
            $data = $request->sanitizedObject();
            ${{$modelVariableName}} = $this->repo::store($data);
            return $this->api->success()->message('{{$modelTitle}} Created')->payload(${{$modelVariableName}})->send();
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return $this->api->failed()->message($exception->getMessage())->payload([])->code(500)->send();
        }
    }

    /**
     * Display the specified resource.
     *
     * {{"@"}}param Request $request
     * {{"@"}}param {{$modelBaseName}} ${{$modelVariableName}}
     * {{"@"}}return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, {{$modelBaseName}} ${{$modelVariableName}})
    {
        try {
            $payload = $this->repo::init(${{$modelVariableName}})->show($request);
            return $this->api->success()
                        ->message("{{$modelTitle}} ${{$modelVariableName}}->id")
                        ->payload($payload)->send();
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return $this->api->failed()->message($exception->getMessage())->send();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * {{"@"}}param Update{{$modelBaseName}} $request
     * {{"@"}}param {{$modelBaseName}} ${{$modelVariableName}}
     * {{"@"}}return \Illuminate\Http\JsonResponse
     */
    public function update(Update{{$modelBaseName}} $request, {{$modelBaseName}} ${{$modelVariableName}})
    {
        try {
            $data = $request->sanitizedObject();
            $res = $this->repo::init(${{$modelVariableName}})->update($data);
            return $this->api->success()->message("{{$modelTitle}} has been updated")->payload($res)->code(200)->send();
        } catch (\Throwable $exception) {
            \Log::error($exception);
            return $this->api->failed()->code(400)->message($exception->getMessage())->send();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * {{"@"}}param {{$modelBaseName}} ${{$modelVariableName}}
     * {{"@"}}return \Illuminate\Http\JsonResponse
     * {{"@"}}throws \Exception
     */
    public function destroy(Destroy{{$modelBaseName}} $request, {{$modelBaseName}} ${{$modelVariableName}})
    {
        $res = $this->repo::init(${{$modelVariableName}})->destroy();
        return $this->api->success()->message("{{$modelTitle}} has been deleted")->payload($res)->code(200)->send();
    }

    /**
     * The API Function
     * @throws AuthorizationException
     */
    public function assignPermission(Request $request, Role $role): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update',$role);
        $validated = $request->validate([
            'permission' => ["required","array"],
            'permission.id' =>['required','numeric'],
            'permission.checked' =>['required','boolean']
        ]);
        $res = Roles::init($role)->assignPermission($validated['permission']);
        return $this->api->success()->message("Role assignment updated")->payload($res)->send();
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
@endif
}
