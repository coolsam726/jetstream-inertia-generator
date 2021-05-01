@php
    $translatableColumns = $columns->filter(function($column) use ($translatable) {
        return in_array($column['name'], $translatable->toArray());
    });
    $standardColumn = $columns->reject(function($column) use ($translatable) {
        return in_array($column['name'], $translatable->toArray());
    });
@endphp
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define({{ $modelFullName }}::class, static function (Faker\Generator $faker) {
    return [
        @foreach($standardColumn as $col)'{{ $col['name'] }}' => {!! $col['faker'] !!},
        @endforeach

        @foreach($translatableColumns as $col)'{{ $col['name'] }}' => ['en' => {!! $col['faker'] !!}],
        @endforeach

    ];
});
