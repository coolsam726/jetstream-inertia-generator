@php echo "<?php";
@endphp


namespace {{ $controllerNamespace }};
@php
    $activation = $columns->search(function ($column, $key) {
            return $column['name'] === 'activated';
        }) !== false;
@endphp

@if($export)use App\Exports\{{$exportBaseName}};
@endif
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\{{ $modelWithNamespaceFromDefault }}\Destroy{{ $modelBaseName }};
use App\Http\Requests\Admin\{{ $modelWithNamespaceFromDefault }}\Index{{ $modelBaseName }};
use App\Http\Requests\Admin\{{ $modelWithNamespaceFromDefault }}\Store{{ $modelBaseName }};
use App\Http\Requests\Admin\{{ $modelWithNamespaceFromDefault }}\Update{{ $modelBaseName }};
use {{ $modelFullName }};
@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
use {{ $belongsToMany['related_model'] }};
@endforeach
@endif
@endif
@if($activation)use Savannabits\AdminAuth\Activation\Facades\Activation;
@endif
@if($activation)use Savannabits\AdminAuth\Services\ActivationService;
@endif
use Savannabits\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;
@if($export)use Maatwebsite\Excel\Facades\Excel;
@endif
@if($export)use Symfony\Component\HttpFoundation\BinaryFileResponse;
@endif

class {{ $controllerBaseName }} extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * {{'@'}}param  Index{{ $modelBaseName }} $request
     * {{'@'}}return array|Factory|View
     */
    public function index(Index{{ $modelBaseName }} $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create({{ $modelBaseName }}::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['{!! implode('\', \'', $columnsToQuery) !!}'],

            // set columns to searchIn
            ['{!! implode('\', \'', $columnsToSearchIn) !!}']
        );

        if ($request->ajax()) {
            return ['data' => $data, 'activation' => Config::get('admin-auth.activation_enabled')];
        }

        return view('admin.{{ $modelDotNotation }}.index', ['data' => $data, 'activation' => Config::get('admin-auth.activation_enabled')]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * {{'@'}}return Factory|View
     * {{'@'}}throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('admin.{{ $modelDotNotation }}.create');

@if (count($relations))
        return view('admin.{{ $modelDotNotation }}.create',[
            'activation' => Config::get('admin-auth.activation_enabled'),
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
            '{{ $belongsToMany['related_table'] }}' => {{ $belongsToMany['related_model_name'] }}::all(),
@endforeach
@endif
        ]);
@else
        return view('admin.{{ $modelDotNotation }}.create');
@endif
    }

    /**
     * Store a newly created resource in storage.
     *
     * {{'@'}}param  Store{{ $modelBaseName }} $request
     * {{'@'}}return array|RedirectResponse|Redirector
     */
    public function store(Store{{ $modelBaseName }} $request)
    {
        // Sanitize input
        $sanitized = $request->getModifiedData();

        // Store the {{ $modelBaseName }}
        ${{ $modelVariableName }} = {{ $modelBaseName }}::create($sanitized);

@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
        // But we do have a {{ $belongsToMany['related_table'] }}, so we need to attach the {{ $belongsToMany['related_table'] }} to the {{ $modelVariableName }}
        ${{ $modelVariableName }}->{{ $belongsToMany['related_table'] }}()->sync(collect($request->input('{{ $belongsToMany['related_table'] }}', []))->map->id->toArray());
@endforeach

@endif
@endif
        if ($request->ajax()) {
            return ['redirect' => url('admin/{{ $resource }}'), 'message' => trans('savannabits/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/{{ $resource }}');
    }

    /**
     * Display the specified resource.
     *
     * {{'@'}}param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * {{'@'}}return void
     * {{'@'}}throws AuthorizationException
     */
    public function show({{ $modelBaseName }} ${{ $modelVariableName }})
    {
        $this->authorize('admin.{{ $modelDotNotation }}.show', ${{ $modelVariableName }});

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * {{'@'}}param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * {{'@'}}return Factory|View
     * {{'@'}}throws AuthorizationException
     */
    public function edit({{ $modelBaseName }} ${{ $modelVariableName }})
    {
        $this->authorize('admin.{{ $modelDotNotation }}.edit', ${{ $modelVariableName }});

@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
        ${{ $modelVariableName }}->load('{{ $belongsToMany['related_table'] }}');
@endforeach

@endif
@endif
        return view('admin.{{ $modelDotNotation }}.edit', [
            '{{ $modelVariableName }}' => ${{ $modelVariableName }},
            'activation' => Config::get('admin-auth.activation_enabled'),
@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
            '{{ $belongsToMany['related_table'] }}' => {{ $belongsToMany['related_model_name'] }}::all(),
@endforeach
@endif
@endif
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * {{'@'}}param  Update{{ $modelBaseName }} $request
     * {{'@'}}param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * {{'@'}}return array|RedirectResponse|Redirector
     */
    public function update(Update{{ $modelBaseName }} $request, {{ $modelBaseName }} ${{ $modelVariableName }})
    {
        // Sanitize input
        $sanitized = $request->getModifiedData();

        // Update changed values {{ $modelBaseName }}
        ${{ $modelVariableName }}->update($sanitized);

@if (count($relations))
@if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)
        // But we do have a {{ $belongsToMany['related_table'] }}, so we need to attach the {{ $belongsToMany['related_table'] }} to the {{ $modelVariableName }}
        if($request->input('{{ $belongsToMany['related_table'] }}')) {
            ${{ $modelVariableName }}->{{ $belongsToMany['related_table'] }}()->sync(collect($request->input('{{ $belongsToMany['related_table'] }}', []))->map->id->toArray());
        }
@endforeach
@endif
@endif

        if ($request->ajax()) {
            return ['redirect' => url('admin/{{ $resource }}'), 'message' => trans('savannabits/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/{{ $resource }}');
    }

    /**
     * Remove the specified resource from storage.
     *
     * {{'@'}}param  Destroy{{ $modelBaseName }} $request
     * {{'@'}}param  {{ $modelBaseName }} ${{ $modelVariableName }}
     * {{'@'}}return ResponseFactory|RedirectResponse|Response
     * {{'@'}}throws Exception
     */
    public function destroy(Destroy{{ $modelBaseName }} $request, {{ $modelBaseName }} ${{ $modelVariableName }})
    {
        ${{ $modelVariableName }}->delete();

        if ($request->ajax()) {
            return response(['message' => trans('savannabits/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    @if($activation)/**
    * Resend activation e-mail
    *
    * {{'@'}}param Request $request
    * {{'@'}}param {{ $modelBaseName }} ${{ $modelVariableName }}
    * {{'@'}}return array|RedirectResponse
    */
    public function resendActivationEmail(Request $request, ActivationService $activationService, {{ $modelBaseName }} ${{ $modelVariableName }})
    {
        if(Config::get('admin-auth.activation_enabled')) {

            $response = $activationService->handle(${{ $modelVariableName }});
            if($response == Activation::ACTIVATION_LINK_SENT) {
                if ($request->ajax()) {
                    return ['message' => trans('savannabits/admin-ui::admin.operation.succeeded')];
                }

                return redirect()->back();
            } else {
                if ($request->ajax()) {
                    abort(409, trans('savannabits/admin-ui::admin.operation.failed'));
                }

                return redirect()->back();
            }
        } else {
            if ($request->ajax()) {
                abort(400, trans('savannabits/admin-ui::admin.operation.not_allowed'));
            }

            return redirect()->back();
        }
    }
@endif
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
