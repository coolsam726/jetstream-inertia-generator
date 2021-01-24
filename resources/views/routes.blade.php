
/* Auto-generated admin routes */

Route::group(["prefix" => config('savadmin.app.prefix',''),
    "namespace" => "Admin",
    "as" => config('savadmin.app.prefix').".",'middleware' => ['auth','verified']],function() {
    Route::group(['as' => "{{$modelRouteName}}.", 'prefix' => "{{$modelRouteName}}"], function() {
        {!! str_pad("Route::get('',", 10) !!}'{{ $controllerClassName }}@index')->name('index');
    });
});
