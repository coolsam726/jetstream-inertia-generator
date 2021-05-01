<?php namespace Savannabits\JetstreamInertiaGenerator\Generators;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ViewFullForm extends ViewGenerator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jig:generate:full-form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a full-form view template';

    /**
     * Path for view
     *
     * @var string
     */
    protected string $view = 'full-form';

    /**
     * Path for js view
     *
     * @var string
     */
    protected string $viewJs = 'form-js';

    /**
     * Name of view, will be used in directory
     *
     * @var string
     */
    protected string $fileName;

    /**
     * Route to process form
     *
     * @var string
     */
    protected string $route;

    /**
     * @var string
     */
    protected string $formJsRelativePath;

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
            $this->view = 'templates.'.$template.'.full-form';
            $this->viewJs = 'templates.'.$template.'.form-js';
        }

        $this->fileName = $this->option('file-name') ?: $this->modelViewsDirectory;
        $this->formJsRelativePath = str_replace([DIRECTORY_SEPARATOR, '/', '\\'], '-', $this->fileName);
        if (!$this->option('file-name')) {
            $this->fileName = $this->fileName . DIRECTORY_SEPARATOR . 'form';
        }

        $this->route = $this->option('route');
        if (!$this->route){
            if ($this->option('file-name')){
                $this->route = 'admin/'.$this->fileName;
            } else {
                $this->route = 'admin/'.$this->resource.'/update';
            }
        }

        $viewPath = resource_path('views/admin/'.$this->fileName.'.blade.php');
        if ($this->alreadyExists($viewPath) && !$force) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            if ($this->alreadyExists($viewPath) && $force) {
                $this->warn('File '.$viewPath.' already exists! File will be deleted.');
                $this->files->delete($viewPath);
            }

            $this->makeDirectory($viewPath);

            $this->files->put($viewPath, $this->buildForm());

            $this->info('Generating '.$viewPath.' finished');
        }

        $formJsPath = resource_path('js/admin/'.$this->formJsRelativePath.'/Form.js');
        $bootstrapJsPath = resource_path('js/admin/index.js');

        if ($this->alreadyExists($formJsPath) && !$force) {
            $this->error('File '.$formJsPath.' already exists!');
        } else {
            if ($this->alreadyExists($formJsPath) && $force) {
                $this->warn('File '.$formJsPath.' already exists! File will be deleted.');
                $this->files->delete($formJsPath);
            }

            $this->makeDirectory($formJsPath);

            $this->files->put($formJsPath, $this->buildFormJs());
            $this->info('Generating '.$formJsPath.' finished');

        }

		$indexJsPath = resource_path('js/admin/'.$this->formJsRelativePath.'/index.js');
		if ($this->alreadyExists($indexJsPath) && !$force) {
			$this->error('File '.$indexJsPath.' already exists!');
		} else {
			if ($this->alreadyExists($indexJsPath) && $force) {
				$this->warn('File '.$indexJsPath.' already exists! File will be deleted.');
				$this->files->delete($indexJsPath);
			}
			$this->makeDirectory($indexJsPath);
		}

		if ($this->appendIfNotAlreadyAppended($indexJsPath, "import './Form';".PHP_EOL)){
			$this->info('Appending Form to '.$indexJsPath.' finished');
		};
		if ($this->appendIfNotAlreadyAppended($bootstrapJsPath, "import './". $this->formJsRelativePath ."';".PHP_EOL)){
			$this->info('Appending '.$this->formJsRelativePath.'/index.js to '.$bootstrapJsPath.' finished');
		};

    }

    protected function buildForm(): string
    {

        return view('jig::'.$this->view, [
            'modelBaseName' => $this->modelBaseName,
            'modelVariableName' => $this->modelVariableName,
            'route' => $this->route,
            'modelJSName' => $this->formJsRelativePath,
            'modelDotNotation' => $this->modelDotNotation,
            'modelLangFormat' => $this->modelLangFormat,
            'modelTitle' => $this->readColumnsFromTable($this->tableName)->filter(function($column){
                return in_array($column['name'], ['title', 'name', 'first_name', 'email']);
            })->first(null, ['name'=>'id'])['name'],

            'columns' => $this->getVisibleColumns($this->tableName, $this->modelVariableName)->sortByDesc(function($column) {
                return $column['type'] == "json";
            }),
            'hasTranslatable' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return $column['type'] == "json";
            })->count() > 0,
            'translatableTextarea' => ['perex', 'text'],
            'relations' => $this->relations,
        ])->render();
    }

    protected function buildFormJs(): string
    {
        return view('jig::'.$this->viewJs, [
            'modelJSName' => $this->formJsRelativePath,

            'columns' => $this->getVisibleColumns($this->tableName, $this->modelVariableName),
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a code for the given model'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
            ['file-name', 'nm', InputOption::VALUE_OPTIONAL, 'Specify a blade file path'],
            ['route', 'r', InputOption::VALUE_OPTIONAL, 'Specify custom route for form'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating full form'],
        ];
    }

}
