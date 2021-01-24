@php echo "<?php"
@endphp


namespace {{ $modelNameSpace }};
@php
    $hasRoles = false;
    if(count($relations) && count($relations['belongsToMany'])) {
        $hasRoles = $relations['belongsToMany']->filter(function($belongsToMany) {
            return $belongsToMany['related_table'] == 'roles';
        })->count() > 0;
        $relations['belongsToMany'] = $relations['belongsToMany']->reject(function($belongsToMany) {
            return $belongsToMany['related_table'] == 'roles';
        });
    }
@endphp

use Savannabits\AdminAuth\Activation\Contracts\CanActivate as CanActivateContract;
use Savannabits\AdminAuth\Activation\Traits\CanActivate;
use Savannabits\AdminAuth\Notifications\ResetPassword;
@if($translatable->count() > 0)use Savannabits\Translatable\Traits\HasTranslations;
@endif
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
@if($hasSoftDelete)use Illuminate\Database\Eloquent\SoftDeletes;
@endif
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
@if($hasRoles)use Spatie\Permission\Traits\HasRoles;
@endif

class {{ $modelBaseName }} extends Authenticatable implements CanActivateContract
{
    use Notifiable;
    use CanActivate;
    @if($hasSoftDelete)use SoftDeletes;
    @endif
@if($hasRoles)use HasRoles;
@endif
@if($translatable->count() > 0)use HasTranslations;
@endif

    @if (!is_null($tableName))protected $table = '{{ $tableName }}';
    @endif

    @if ($fillable)protected $fillable = [
    @foreach($fillable as $f)
    '{{ $f }}',
    @endforeach

    ];
    @endif

    @if ($hidden)protected $hidden = [
    @foreach($hidden as $h)
    '{{ $h }}',
    @endforeach

    ];
    @endif

    @if ($dates)protected $dates = [
    @foreach($dates as $date)
    '{{ $date }}',
    @endforeach

    ];
    @endif

    @if ($translatable->count() > 0)// these attributes are translatable
    public $translatable = [
    @foreach($translatable as $translatableField)
    '{{ $translatableField }}',
    @endforeach

    ];
    @endif

    @if (!$timestamps)public $timestamps = false;
    @endif

    protected $appends = ['full_name', 'resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute() {
        return url('/admin/{{$resource}}/'.$this->getKey());
    }

    public function getFullNameAttribute() {
        return $this->first_name." ".$this->last_name;
    }

    /**
     * Send the password reset notification.
     *
     * {{'@'}}param string $token
     * {{'@'}}return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(app( ResetPassword::class, ['token' => $token]));
    }

    @if (count($relations))/* ************************ RELATIONS ************************ */

    @if (count($relations['belongsToMany']))
@foreach($relations['belongsToMany'] as $belongsToMany)/**
    * Relation to {{ $belongsToMany['related_model_name_plural'] }}
    *
    * {{'@'}}return BelongsToMany
    */
    public function {{ $belongsToMany['related_table'] }}() {
        return $this->belongsToMany({{ $belongsToMany['related_model_class'] }}, '{{ $belongsToMany['relation_table'] }}', '{{ $belongsToMany['foreign_key'] }}', '{{ $belongsToMany['related_key'] }}');
    }

@endforeach
    @endif
    @endif

}
