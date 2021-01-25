
/* Auto-generated {{$modelRouteName}} admin routes */
Route::middleware(['auth:sanctum', 'verified'])->prefix("admin")->as("admin")->resource('{{$modelRouteName}}', \App\Http\Controllers\Admin\{{$controllerClassName}}::class);
