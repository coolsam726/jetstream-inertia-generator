<?php namespace Savannabits\JetstreamInertiaGenerator\Generators;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class Model extends ClassGenerator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jig:generate:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a model class';

    /**
     * Path for view
     *
     * @var string
     */
    protected string $view = 'model';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $force = $this->option('force');
        //TODO check if exists
        //TODO make global for all generator
        //TODO also with prefix
        if(!empty($template = $this->option('template'))) {
            $this->view = 'templates.'.$template.'.model';
        }

        if(!empty($belongsToMany = $this->option('belongs-to-many'))) {
            $this->setBelongToManyRelation($belongsToMany);
        }

        if ($this->generateClass($force)){
            $this->info('Generating '.$this->classFullName.' finished');
        }

        /*Generate a Factory Skeleton for the model*/
        $this->call('make:factory', [
            "name" => $this->modelBaseName."Factory",
            '--model' => $this->modelBaseName,
        ]);
        $this->info('Generating '.$this->modelBaseName."Factory".' finished');
    }

    /**
     * @return string
     */
    protected function buildClass(): string
    {
        //Set belongsTo Relations

        $this->setBelongsToRelations();

        return view('jig::'.$this->view, [
            'modelBaseName' => $this->classBaseName,
            'modelNameSpace' => $this->classNamespace,
            // if table name differs from the snake case plural form of the classname, then we need to specify the table name
            'tableName' => ($this->tableName !== Str::snake(Str::plural($this->classBaseName))) ? $this->tableName : null,

            'dates' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return $column['type'] == "date";
            })->pluck('name'),
            'datetimes' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return $column['type'] == "datetime";
            })->pluck('name'),

            'booleans' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return $column['type'] == "boolean" || $column['type'] == "bool";
            })->pluck('name'),
            'fillable' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return !in_array($column['name'], [
                    'id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'password',
                    'remember_token',
                    'slug',
                    'email_verified_at',
                    'two_factor_recovery_codes',
                    'two_factor_secret',
                    'api_key',
                ]);
            })->pluck('name'),
            'searchable' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return !in_array($column['name'], [
                    'created_at',
                        'updated_at',
                        'deleted_at',
                        'password',
                        'remember_token',
                        'two_factor_recovery_codes',
                        'two_factor_secret',
                        'api_key',
                    ])
                    && !in_array($column["type"],["json"]);
            })->pluck('name'),
            'hidden' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return in_array($column['name'], [
                    'password',
                    'remember_token',
                    'two_factor_recovery_codes',
                    'two_factor_secret',
                    'api_key',
                ]);
            })->pluck('name'),
            'translatable' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return $column['type'] == "json";
            })->pluck('name'),
            'timestamps' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return in_array($column['name'], [
                    'created_at',
                    'updated_at',
                    'email_verified_at',
                    'deleted_at',
                ]);
            })->count() > 0,
            'hasSoftDelete' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return $column['name'] == "deleted_at";
            })->count() > 0,
            'routeBaseName' => Str::kebab(Str::plural($this->classBaseName)),
            'resource' => $this->resource,
            'relations' => $this->relations,
        ])->render();

    }

    protected function getOptions() {
        return [
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
            ['belongs-to-many', 'btm', InputOption::VALUE_OPTIONAL, 'Specify belongs to many relations'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating model'],
        ];
    }

    public function generateClassNameFromTable($tableName) {
        return Str::studly(Str::singular($tableName));
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace.'\Models';
    }
}
