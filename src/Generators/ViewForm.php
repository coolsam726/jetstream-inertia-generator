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
    protected string $create = 'create';

    /**
     * Path for edit view
     *
     * @var string
     */
    protected string $show = 'show';
    protected string $edit = 'edit';
    protected ?string $assignPermissions = null;

    /**
     * Path for form view
     *
     * @var string
     */
    protected string $showForm = 'show-form';
    protected string $createForm = 'create-form';
    protected string $editForm = 'edit-form';

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
            $this->create = 'templates.'.$template.'.create';
            $this->createForm = 'templates.'.$template.'.create-form';
            $this->edit = 'templates.'.$template.'.edit';
            $this->editForm = 'templates.'.$template.'.edit-form';
            $this->show = 'templates.'.$template.'.show';
            $this->showForm = 'templates.'.$template.'.show-form';
            $this->assignPermissions = 'templates.'.$template.'.assign-permissions';
        }

        if(!empty($belongsToMany = $this->option('belongs-to-many'))) {
            $this->setBelongToManyRelation($belongsToMany);
        }
        /*Make Create Form*/
        $viewPath = resource_path('js/Pages/'.$this->modelPlural.'/CreateForm.vue');
        if ($this->alreadyExists($viewPath) && !$force) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            if ($this->alreadyExists($viewPath) && $force) {
                $this->warn('File '.$viewPath.' already exists! File will be deleted.');
                $this->files->delete($viewPath);
            }
            $this->makeDirectory($viewPath);
            $this->files->put($viewPath, $this->buildForm($this->createForm));
            $this->info('Generating '.$viewPath.' finished');
        }
        /*Make Create Page*/
        $viewPath = resource_path('js/Pages/'.$this->modelPlural.'/Create.vue');
        if ($this->alreadyExists($viewPath) && !$force) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            if ($this->alreadyExists($viewPath) && $force) {
                $this->warn('File '.$viewPath.' already exists! File will be deleted.');
                $this->files->delete($viewPath);
            }
            $this->makeDirectory($viewPath);
            $this->files->put($viewPath, $this->buildForm($this->create));
            $this->info('Generating '.$viewPath.' finished');
        }
        //Make edit form
        $viewPath = resource_path('js/Pages/'.$this->modelPlural.'/EditForm.vue');
        if ($this->alreadyExists($viewPath) && !$force) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            if ($this->alreadyExists($viewPath) && $force) {
                $this->warn('File '.$viewPath.' already exists! File will be deleted.');
                $this->files->delete($viewPath);
            }
            $this->makeDirectory($viewPath);
            $this->files->put($viewPath, $this->buildForm($this->editForm));
            $this->info('Generating '.$viewPath.' finished');
        }

        //Make Assign Permissions form
        if ($this->tableName ==='roles' && $this->assignPermissions) {
            $viewPath = resource_path('js/Pages/'.$this->modelPlural.'/AssignPerms.vue');
            if ($this->alreadyExists($viewPath) && !$force) {
                $this->error('File '.$viewPath.' already exists!');
            } else {
                if ($this->alreadyExists($viewPath) && $force) {
                    $this->warn('File '.$viewPath.' already exists! File will be deleted.');
                    $this->files->delete($viewPath);
                }
                $this->makeDirectory($viewPath);
                $this->files->put($viewPath, $this->buildForm($this->assignPermissions));
                $this->info('Generating '.$viewPath.' finished');
            }
        }

        //Make edit Page
        $viewPath = resource_path('js/Pages/'.$this->modelPlural.'/Edit.vue');
        if ($this->alreadyExists($viewPath) && !$force) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            if ($this->alreadyExists($viewPath) && $force) {
                $this->warn('File '.$viewPath.' already exists! File will be deleted.');
                $this->files->delete($viewPath);
            }
            $this->makeDirectory($viewPath);
            $this->files->put($viewPath, $this->buildForm($this->edit));
            $this->info('Generating '.$viewPath.' finished');
        }

        // Make Show Form
        $viewPath = resource_path('js/Pages/'.$this->modelPlural.'/ShowForm.vue');
        if ($this->alreadyExists($viewPath) && !$force) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            if ($this->alreadyExists($viewPath) && $force) {
                $this->warn('File '.$viewPath.' already exists! File will be deleted.');
                $this->files->delete($viewPath);
            }
            $this->makeDirectory($viewPath);
            $this->files->put($viewPath, $this->buildForm($this->showForm));
            $this->info('Generating '.$viewPath.' finished');
        }

        // Make Show Page
        $viewPath = resource_path('js/Pages/'.$this->modelPlural.'/Show.vue');
        if ($this->alreadyExists($viewPath) && !$force) {
            $this->error('File '.$viewPath.' already exists!');
        } else {
            if ($this->alreadyExists($viewPath) && $force) {
                $this->warn('File '.$viewPath.' already exists! File will be deleted.');
                $this->files->delete($viewPath);
            }
            $this->makeDirectory($viewPath);
            $this->files->put($viewPath, $this->buildForm($this->show));
            $this->info('Generating '.$viewPath.' finished');
        }

    }

    protected function isUsedTwoColumnsLayout() : bool {
        return false;
    }

    protected function buildForm(?string $type=null): string
    {
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
        return view('jig::'.$type, [
            'modelBaseName' => $this->modelBaseName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
            'modelPlural' => $this->modelPlural,
            'modelTitle' => $this->titleSingular,
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

    protected function buildShow(): string
    {

        $belongsTos = $this->setBelongsToRelations();
        $relatables = $this->getVisibleColumns($this->tableName, $this->modelVariableName)->filter(function($column) use($belongsTos) {
            return in_array($column['name'],$belongsTos->pluck('foreign_key')->toArray());
        })->keyBy('name');
        $columns = $this->readColumnsFromTable($this->tableName)->reject(function($column) use($belongsTos) {
            return in_array($column['name'],$belongsTos->pluck('foreign_key')->toArray());
        })->map(function($column) {
            $column["label"] = str_replace("_"," ",Str::title($column['name']));
            return $column;
        })->keyBy('name');
        return view('jig::'.$this->show, [
            'modelBaseName' => $this->modelBaseName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
            'modelVariableName' => $this->modelVariableName,
            'modelPlural' => $this->modelPlural,
            'modelTitleSingular' => $this->titleSingular,
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

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a code for the given model'],
            ['belongs-to-many', 'btm', InputOption::VALUE_OPTIONAL, 'Specify belongs to many relations'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating form'],
        ];
    }

}
