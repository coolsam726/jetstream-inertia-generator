/* Auto-generated {{$modelRouteName}} api routes */
Route::group(["middleware"=>['auth:sanctum', 'verified'],'as' => 'api.'], function () {
    Route::post('/roles/{role}/assign-permission', [\App\Http\Controllers\API\RoleController::class,'assignPermission'])->name('roles.assign-permission');
    Route::get('/{{$modelRouteName}}/dt', [\App\Http\Controllers\API\{{$controllerClassName}}::class,'dt'])->name('{{$modelRouteName}}.dt');
    Route::apiResource('/{{$modelRouteName}}', \App\Http\Controllers\API\{{$controllerClassName}}::class)->parameters(["{{$modelRouteName}}" => "{{$modelVariableName}}"]);
});
