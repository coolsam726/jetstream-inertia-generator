<?php namespace Savannabits\JetstreamInertiaGenerator\Generators;

use Symfony\Component\Console\Input\InputOption;

class IndexRequest extends ClassGenerator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jig:generate:request:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an Index request class';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $force = $this->option('force');

        if ($this->generateClass($force)){
            $this->info('Generating '.$this->classFullName.' finished');
        }
    }

    protected function buildClass() {

        return view('jig::index-request', [
            'modelBaseName'                 => $this->modelBaseName,
            'modelDotNotation'              => $this->modelDotNotation,
            'modelWithNamespaceFromDefault' => $this->modelWithNamespaceFromDefault,
            'modelVariableName'             => $this->modelVariableName,

            'columnsToQuery' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return !($column['type'] == 'text' || $column['name'] == "password" || $column['name'] == "remember_token" || $column['name'] == "slug" || $column['name'] == "created_at" || $column['name'] == "updated_at" || $column['name'] == "deleted_at");
            })->pluck('name')->toArray(),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a code for the given model'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating request'],
        ];
    }

    public function generateClassNameFromTable($tableName) {
        return 'Index'.$this->modelBaseName;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Requests\Api\\'.$this->modelWithNamespaceFromDefault;
    }

}
