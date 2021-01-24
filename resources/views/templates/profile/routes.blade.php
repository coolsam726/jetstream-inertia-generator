
/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('Admin')->name('admin/')->group(static function() {
        {!! str_pad("Route::get('/profile',", 60) !!}'{{ $controllerPartiallyFullName }}@editProfile')->name('edit-profile');
        {!! str_pad("Route::post('/profile',", 60) !!}'{{ $controllerPartiallyFullName }}@updateProfile')->name('update-profile');
        {!! str_pad("Route::get('/password',", 60) !!}'{{ $controllerPartiallyFullName }}@editPassword')->name('edit-password');
        {!! str_pad("Route::post('/password',", 60) !!}'{{ $controllerPartiallyFullName }}@updatePassword')->name('update-password');
    });
});