<?php namespace Savannabits\JetstreamInertiaGenerator\Generators;

use Savannabits\JetstreamInertiaGenerator\Generators\Traits\FileManipulations;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class Policy extends ClassGenerator {

    use FileManipulations;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jig:generate:policy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an Repository class';

    /**
     * Path for view
     *
     * @var string
     */
    protected string $view = 'policy';

    /**
     * Controller has also export method
     *
     * @return mixed
     */
    protected bool $export = false;

    /**
     * Controller has also bulk options method
     *
     * @return mixed
     */
    protected bool $withoutBulk = true;

    public function handle()
    {
        $force = $this->option('force');

        if($this->option('with-export')){
            $this->export = true;
        }

        if($this->option('without-bulk')){
            $this->withoutBulk = true;
        }
        if(!empty($template = $this->option('template'))) {
            $this->view = 'templates.'.$template.'.policy';
        }
        if ($this->generateClass($force)){

            $this->info('Generating '.$this->classFullName.' finished');
        }

    }

    protected function buildClass(): string {

        //Set belongsTo Relations
        $this->setBelongsToRelations();

        return view('jig::'.$this->view, [
            'policyBaseName' => $this->classBaseName,
            'policyNamespace' => $this->classNamespace,
            'modelBaseName' => $this->modelBaseName,
            'modelFullName' => $this->modelFullName,
            'modelPlural' => $this->modelPlural,
            'modelTitle' => $this->titleSingular,
            'titlePlural' => $this->titlePlural,
            'modelVariableName' => $this->modelVariableName,
            'modelVariableNamePlural' => Str::plural($this->modelVariableName),
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
            'modelViewsDirectory' => $this->modelViewsDirectory,
            'modelDotNotation' => $this->modelDotNotation,
            'modelWithNamespaceFromDefault' => $this->modelWithNamespaceFromDefault,
            'export' => $this->export,
            'withoutBulk' => $this->withoutBulk,
            'exportBaseName' => $this->exportBaseName,
            'resource' => $this->resource,
            // validation in store/update
            'columns' => $this->getVisibleColumns($this->tableName, $this->modelVariableName),
            'relations' => $this->relations,
            'hasSoftDelete' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                    return $column['name'] == "deleted_at";
            })->count() > 0,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a code for the given model'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
            ['belongs-to-many', 'btm', InputOption::VALUE_OPTIONAL, 'Specify belongs to many relations'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating controller'],
            ['model-with-full-namespace', 'fnm', InputOption::VALUE_OPTIONAL, 'Specify model with full namespace'],
            ['with-export', 'e', InputOption::VALUE_NONE, 'Generate an option to Export as Excel'],
            ['without-bulk', 'wb', InputOption::VALUE_NONE, 'Generate without bulk options'],
        ];
    }

    public function generateClassNameFromTable($tableName): string
    {
        return Str::studly(Str::singular($tableName))."Policy";
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace(string $rootNamespace) : string
    {
        return $rootNamespace.'\Policies';
    }
}
