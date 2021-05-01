<?php namespace Savannabits\JetstreamInertiaGenerator\Generators;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class Export extends ClassGenerator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jig:generate:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an export class';

    /**
     * Path for view
     *
     * @var string
     */
    protected string $view = 'export';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $force = $this->option('force');

        if(!empty($template = $this->option('template'))) {
            $this->view = 'templates.'.$template.'.export';
        }

        if ($this->generateClass($force)){
            $this->info('Generating '.$this->classFullName.' finished');
        }

    }

    protected function buildClass(): string {
        return view('jig::'.$this->view, [
            'exportNamespace' => $this->classNamespace,
            'modelFullName' => $this->modelFullName,
            'classBaseName' => $this->exportBaseName,
            'modelBaseName' => $this->modelBaseName,
            'modelVariableName' => $this->modelVariableName,
            'modelLangFormat' => $this->modelLangFormat,
            'columnsToExport' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return !($column['name'] == "password" || $column['name'] == "remember_token" || $column['name'] == "updated_at" || $column['name'] == "created_at"  || $column['name'] == "deleted_at");
            })->pluck('name')->toArray(),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating request'],
            ['model-with-full-namespace', 'fnm', InputOption::VALUE_OPTIONAL, 'Specify model with full namespace'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
        ];
    }

    public function generateClassNameFromTable($tableName) {
        return $this->exportBaseName;
    }
    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace(string $rootNamespace) : string
    {
        return $rootNamespace.'\Exports';
    }
}
