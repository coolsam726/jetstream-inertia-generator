<?php namespace Savannabits\JetstreamInertiaGenerator\Generators;

use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ViewForm extends ViewGenerator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jig:generate:form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate create and edit view templates';

    /**
     * Path for create view
     *
     * @var string
     */
    protected $create = 'create';

    /**
     * Path for edit view
     *
     * @var string
     */
    protected $show = 'show';

    /**
     * Path for form view
     *
     * @var string
     */
    protected $form = 'form';

    /**
     * Path for form right view
     *
     * @var string
     */
    protected $formRight = 'form-right';

    /**
     * Path for js view
     *
     * @var string
     */
    protected $formJs = 'form-js';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $force = $this->option('force');

        //TODO check if exists
        //TODO make global for all generator
        //TODO also with prefix
        if(!empty($template = $this->option('template'))) {
            $this->create = 'templates.'.$template.'.create';
            $this->edit = 'templates.'.$template.'.edit';
            $this->form = 'templates.'.$template.'.form';
            $this->formRight = 'templates.'.$template.'form-right';
            $this->formJs = 'templates.'.$template.'.form-js';
        }

        if(!empty($belongsToMany = $this->option('belongs-to-many'))) {
            $this->setBelongToManyRelation($belongsToMany);
        }

        $viewPath = resource_path('views/backend/'.$this->modelViewsDirectory.'/form.blade.php');
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

        $viewPath = resource_path('views/backend/'.$this->modelViewsDirectory.'/show.blade.php');
        if ($this->alreadyExists($viewPath) && !$force) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            if ($this->alreadyExists($viewPath) && $force) {
                $this->warn('File '.$viewPath.' already exists! File will be deleted.');
                $this->files->delete($viewPath);
            }

            $this->makeDirectory($viewPath);

            $this->files->put($viewPath, $this->buildShow());

            $this->info('Generating '.$viewPath.' finished');
        }

        $formJsPath = resource_path('js/backend/'.$this->modelJSName.'.js');

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

		$indexJsPath = resource_path('js/backend/index.js');

		if ($this->appendIfNotAlreadyAppended($indexJsPath, "Vue.component('".$this->modelJSName."-component', () => import(/*webpackChunkName: 'js/".$this->modelJSName."-component'*/'./".$this->modelJSName."'));".PHP_EOL)){
			$this->info('Appending Form to '.$indexJsPath.' finished');
		};
    }

    protected function isUsedTwoColumnsLayout() : bool {
        return false;
    }

    protected function buildForm() {
        $belongsTos = $this->setBelongsToRelations();
        $relatables = $this->getVisibleColumns($this->tableName, $this->modelVariableName)->filter(function($column) use($belongsTos) {
            return in_array($column['name'],$belongsTos->pluck('foreign_key')->toArray());
        })->keyBy('name');
        $columns = $this->getVisibleColumns($this->tableName, $this->modelVariableName)->reject(function($column) use($belongsTos) {
            return in_array($column['name'],$belongsTos->pluck('foreign_key')->toArray()) || $column["name"] ==='slug';
        })->map(function($column) {
            $column["label"] = str_replace("_"," ",Str::title($column['name']));
            return $column;
        })->keyBy('name');

        return view('jig::'.$this->form, [
            'modelBaseName' => $this->modelBaseName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
            'modelPlural' => $this->modelPlural,
            'modelDotNotation' => $this->modelDotNotation,
            'modelLangFormat' => $this->modelLangFormat,
            'columns' => $columns,
            "relatable" => $relatables,
            'hasTranslatable' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return $column['type'] == "json";
            })->count() > 0,
            'translatableTextarea' => ['perex', 'text', 'body'],
            'relations' => $this->relations,
        ])->render();
    }

    protected function buildShow() {

        $belongsTos = $this->setBelongsToRelations();
        $relatables = $this->getVisibleColumns($this->tableName, $this->modelVariableName)->filter(function($column) use($belongsTos) {
            return in_array($column['name'],$belongsTos->pluck('foreign_key')->toArray());
        })->keyBy('name');
        $columns = $this->getVisibleColumns($this->tableName, $this->modelVariableName)->reject(function($column) use($belongsTos) {
            return in_array($column['name'],$belongsTos->pluck('foreign_key')->toArray()) || $column["name"] ==='slug';
        })->map(function($column) {
            $column["label"] = str_replace("_"," ",Str::title($column['name']));
            return $column;
        })->keyBy('name');
        return view('jig::'.$this->show, [
            'modelBaseName' => $this->modelBaseName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
            'modelVariableName' => $this->modelVariableName,
            'modelPlural' => $this->modelPlural,
            'modelViewsDirectory' => $this->modelViewsDirectory,
            'modelDotNotation' => $this->modelDotNotation,
            'modelJSName' => $this->modelJSName,
            'modelLangFormat' => $this->modelLangFormat,
            'resource' => $this->resource,
            'isUsedTwoColumnsLayout' => $this->isUsedTwoColumnsLayout(),

            'modelTitle' => $this->readColumnsFromTable($this->tableName)->filter(function($column){
            	return in_array($column['name'], ['title', 'name', 'first_name', 'email']);
            })->first(null, ['name'=>'id'])['name'],
            'columns' => $columns,
            "relatables" => $relatables,
            'relations' => $this->relations,
            'hasTranslatable' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return $column['type'] == "json";
            })->count() > 0,
        ])->render();
    }

    protected function buildFormJs() {
        $belongsTos = $this->setBelongsToRelations();
        $relatables = $this->getVisibleColumns($this->tableName, $this->modelVariableName)->filter(function($column) use($belongsTos) {
            return in_array($column['name'],$belongsTos->pluck('foreign_key')->toArray());
        })->keyBy('name');
        $columns = $this->getVisibleColumns($this->tableName, $this->modelVariableName)->reject(function($column) use($belongsTos) {
            return in_array($column['name'],$belongsTos->pluck('foreign_key')->toArray())|| $column["name"] ==='slug';
        })->map(function($column) {
            $column["label"] = str_replace("_"," ",Str::title($column['name']));
            return $column;
        })->keyBy('name');
        return view('jig::'.$this->formJs, [
            'modelViewsDirectory' => $this->modelViewsDirectory,
            'modelJSName' => $this->modelJSName,

            'columns' => $columns,
            "relatables" => $relatables,
            "relations" => $this->relations
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a code for the given model'],
            ['belongs-to-many', 'btm', InputOption::VALUE_OPTIONAL, 'Specify belongs to many relations'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating form'],
        ];
    }

}
