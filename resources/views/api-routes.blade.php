/* Auto-generated {{$modelRouteName}} api routes */
Route::group(["middleware"=>['auth:sanctum', 'verified'],'as' => 'api.'], function () {
    Route::get('/{{$modelRouteName}}/dt', [\App\Http\Controllers\API\{{$controllerClassName}}::class,'dt'])->name('{{$modelRouteName}}.dt');
    Route::apiResource('/{{$modelRouteName}}', \App\Http\Controllers\API\{{$controllerClassName}}::class)->parameters(["{{$modelRouteName}}" => "{{$modelVariableName}}"]);
});
