
/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        Route::prefix('{{ $resource }}')->name('{{ $resource }}/')->group(static function() {
            {!! str_pad("Route::get('/',", 60) !!}'{{ $controllerPartiallyFullName }}@index')->name('index');
            {!! str_pad("Route::get('/create',", 60) !!}'{{ $controllerPartiallyFullName }}@create')->name('create');
            {!! str_pad("Route::post('/',", 60) !!}'{{ $controllerPartiallyFullName }}@store')->name('store');
            {!! str_pad("Route::get('/{".$modelVariableName."}/edit',", 60) !!}'{{ $controllerPartiallyFullName }}@edit')->name('edit');
            {!! str_pad("Route::post('/{".$modelVariableName."}',", 60) !!}'{{ $controllerPartiallyFullName }}@update')->name('update');
            {!! str_pad("Route::delete('/{".$modelVariableName."}',", 60) !!}'{{ $controllerPartiallyFullName }}@destroy')->name('destroy');
@if($export)
            {!! str_pad("Route::get('/export',", 60) !!}'{{ $controllerPartiallyFullName }}@export')->name('export');
@endif
            {!! str_pad("Route::get('/{".$modelVariableName."}/resend-activation',", 60) !!}'{{ $controllerPartiallyFullName }}@resendActivationEmail')->name('resendActivationEmail');
        });
    });
});