@php echo "<?php"
@endphp


namespace {{ $controllerNamespace }};

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public ${{ $modelVariableName }};

    /**
     * Guard used for admin user
     *
     * {{'@'}}var string
     */
    protected $guard = 'admin';

    public function __construct()
    {
        // TODO add authorization
        $this->guard = config('admin-auth.defaults.guard');
    }

    /**
     * Get logged user before each method
     *
     * {{'@'}}param Request $request
     */
    protected function setUser($request)
    {
        if (empty($request->user($this->guard))) {
            abort(404, 'Admin User not found');
        }

        $this->{{ $modelVariableName }} = $request->user($this->guard);
    }

    /**
     * Show the form for editing logged user profile.
     *
     * {{'@'}}param Request $request
     * {{'@'}}return Factory|View
     */
    public function editProfile(Request $request)
    {
        $this->setUser($request);

        return view('admin.profile.edit-profile', [
            '{{ $modelVariableName }}' => $this->{{ $modelVariableName }},
        ]);
    }
@php
    $columnsProfile = $columns->reject(function($column) {
        return in_array($column['name'], ['password', 'activated', 'forbidden']);
    });
@endphp

    /**
     * Update the specified resource in storage.
     *
     * {{'@'}}param Request $request
     * {{'@'}}throws ValidationException
     * {{'@'}}return array|RedirectResponse|Redirector
     */
    public function updateProfile(Request $request)
    {
        $this->setUser($request);
        ${{ $modelVariableName }} = $this->{{ $modelVariableName }};

        // Validate the request
        $this->validate($request, [
            @foreach($columnsProfile as $column)'{{ $column['name'] }}' => [{!! implode(', ', (array) $column['serverUpdateRules']) !!}],
            @endforeach

        ]);

        // Sanitize input
        $sanitized = $request->only([
            @foreach($columnsProfile as $column)'{{ $column['name'] }}',
            @endforeach

        ]);

        // Update changed values {{ $modelBaseName }}
        $this->{{ $modelVariableName }}->update($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/profile'), 'message' => trans('savannabits/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/profile');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * {{'@'}}param Request $request
     * {{'@'}}return Factory|View
     */
    public function editPassword(Request $request)
    {
        $this->setUser($request);

        return view('admin.profile.edit-password', [
            '{{ $modelVariableName }}' => $this->{{ $modelVariableName }},
        ]);
    }

@php
    $columnsPassword = $columns->reject(function($column) {
        return !in_array($column['name'], ['password']);
    });
@endphp

    /**
     * Update the specified resource in storage.
     *
     * {{'@'}}param Request $request
     * {{'@'}}throws ValidationException
     * {{'@'}}return array|RedirectResponse|Redirector
     */
    public function updatePassword(Request $request)
    {
        $this->setUser($request);
        ${{ $modelVariableName }} = $this->{{ $modelVariableName }};

        // Validate the request
        $this->validate($request, [
            @foreach($columnsPassword as $column)'{{ $column['name'] }}' => [{!! implode(', ', (array) $column['serverUpdateRules']) !!}],
            @endforeach

        ]);

        // Sanitize input
        $sanitized = $request->only([
            @foreach($columnsPassword as $column)'{{ $column['name'] }}',
            @endforeach

        ]);

        //Modify input, set hashed password
        $sanitized['password'] = Hash::make($sanitized['password']);

        // Update changed values {{ $modelBaseName }}
        $this->{{ $modelVariableName }}->update($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/password'), 'message' => trans('savannabits/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/password');
    }
}
