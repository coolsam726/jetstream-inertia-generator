<?php namespace Savannabits\JetstreamInertiaGenerator\Generators;

use Symfony\Component\Console\Input\InputOption;

class DestroyRequest extends ClassGenerator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jig:generate:request:destroy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Destroy request class';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $force = $this->option('force');

        if ($this->generateClass($force)){
            $this->info('Generating '.$this->classFullName.' finished');
        }
    }

    protected function buildClass() :string {

        return view('jig::destroy-request', [
            'modelBaseName' => $this->modelBaseName,
            'modelFullName'                 => $this->modelFullName,
            'modelDotNotation' => $this->modelDotNotation,
            'modelWithNamespaceFromDefault' => $this->modelWithNamespaceFromDefault,
            'modelVariableName' => $this->modelVariableName,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a code for the given model'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating request'],
        ];
    }

    public function generateClassNameFromTable($tableName) :string {
        return 'Destroy'.$this->modelBaseName;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace(string $rootNamespace) :string
    {
        return $rootNamespace.'\Http\Requests\\'.$this->modelWithNamespaceFromDefault;
    }

}
