/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define({{ $modelFullName }}::class, function (Faker\Generator $faker) {
    return [
        @foreach($columns as $col)@if($col['name'] == 'activated')'{{ $col['name'] }}' => true,
@elseif($col['name'] == 'language')'{{ $col['name'] }}' => 'en',
@else'{{ $col['name'] }}' => {!! $col['faker'] !!},
@endif        @endforeach

    ];
});