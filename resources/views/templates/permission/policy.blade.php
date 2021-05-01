@php echo "<?php\n";
if ($modelVariableName === 'user') $modelVariableName = 'model';
@endphp
namespace {{$policyNamespace}};

use {{$modelFullName}};
@if ($modelVariableName!=='model')
use App\Models\User;
@endif
use Illuminate\Auth\Access\HandlesAuthorization;

class {{$policyBaseName}}
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('{{$modelDotNotation}}.index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  {{$modelBaseName}}  ${{$modelVariableName}}
     * @return mixed
     */
    public function view(User $user, {{$modelBaseName}} ${{$modelVariableName}})
    {
        return $user->hasPermissionTo('{{$modelDotNotation}}.show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  {{$modelBaseName}}  ${{$modelVariableName}}
     * @return mixed
     */
    public function update(User $user, {{$modelBaseName}} ${{$modelVariableName}})
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  {{$modelBaseName}}  ${{$modelVariableName}}
     * @return mixed
     */
    public function delete(User $user, {{$modelBaseName}} ${{$modelVariableName}})
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  {{$modelBaseName}}  ${{$modelVariableName}}
     * @return mixed
     */
    public function restore(User $user, {{$modelBaseName}} ${{$modelVariableName}})
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  {{$modelBaseName}}  ${{$modelVariableName}}
     * @return mixed
     */
    public function forceDelete(User $user, {{$modelBaseName}} ${{$modelVariableName}})
    {
        return false;
    }
}
