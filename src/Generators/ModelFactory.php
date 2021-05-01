<?php namespace Savannabits\JetstreamInertiaGenerator\Generators;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ModelFactory extends FileAppender {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jig:generate:factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Append a new factory';

    /**
     * Path for view
     *
     * @var string
     */
    protected string $view = 'factory';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        //TODO check if exists
        //TODO make global for all generator
        //TODO also with prefix
        if(!empty($template = $this->option('template'))) {
            $this->view = 'templates.'.$template.'.factory';
        }

        if ($this->appendIfNotAlreadyAppended(base_path('database/factories/ModelFactory.php'), $this->buildClass())){
            $this->info('Appending '.$this->modelBaseName.' model to ModelFactory finished');
        }

        if ($this->option('seed')) {
            $this->info('Seeding testing data');
            factory($this->modelFullName , 50)->create();
        }
    }

    protected function buildClass(): string
    {

        return view('jig::'.$this->view, [
            'modelFullName' => $this->modelFullName,

            'columns' => $this->readColumnsFromTable($this->tableName)
                // we skip primary key
                ->filter(function($col){
                    return $col['name'] != 'id';
                })
                ->map(function($col) {
                if($col['name'] == 'deleted_at') {
                    $type = 'null';
                } else if($col['name'] == 'remember_token') {
                    $type = 'null';
                } else {
                    if ($col['type'] == 'date') {
                        $type = '$faker->date()';
                    } elseif ($col['type'] == 'time') {
                        $type = '$faker->time()';
                    } elseif ($col['type'] == 'datetime') {
                        $type = '$faker->dateTime';
                    } elseif ($col['type'] == 'text') {
                        $type = '$faker->text()';
                    } elseif ($col['type'] == 'boolean') {
                        $type = '$faker->boolean()';
                    } elseif ($col['type'] == 'integer' || $col['type'] == 'numeric' || $col['type'] == 'decimal') {
                        $type = '$faker->randomNumber(5)';
                    } elseif ($col['type'] == 'float') {
                        $type = '$faker->randomFloat';
                    } elseif ($col['name'] == 'title') {
                        $type = '$faker->sentence';
                    } elseif ($col['name'] == 'email') {
                        $type = '$faker->email';
                    } elseif ($col['name'] == 'name' || $col['name'] == 'first_name') {
                        $type = '$faker->firstName';
                    } elseif ($col['name'] == 'surname' || $col['name'] == 'last_name') {
                        $type = '$faker->lastName';
                    } elseif ($col['name'] == 'slug') {
                        $type = '$faker->unique()->slug';
                    } elseif ($col['name'] == 'password') {
                        $type = 'bcrypt($faker->password)';
                    } else {
                        $type = '$faker->sentence';
                    }
                }
                return [
                    'name' => $col['name'],
                    'faker' => $type,
                ];
            }),
            'translatable' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return $column['type'] == "json";
            })->pluck('name'),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a code for the given model'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
            ['seed', 's', InputOption::VALUE_OPTIONAL, 'Seeds the table with fake data'],
            ['model-with-full-namespace', 'fnm', InputOption::VALUE_OPTIONAL, 'Specify model with full namespace'],
        ];
    }

}
