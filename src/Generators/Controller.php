<?php namespace Savannabits\JetstreamInertiaGenerator\Generators;

use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Savannabits\JetstreamInertiaGenerator\Generators\Traits\FileManipulations;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Symfony\Component\Console\Input\InputOption;

class Controller extends ClassGenerator {

    use FileManipulations;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jig:generate:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a web controller class';

    /**
     * Path for view
     *
     * @var string
     */
    protected string $view = 'controller';

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
    protected bool $withoutBulk = false;
    protected string $modelTitle;

    public function handle()
    {
        $force = $this->option('force');

        if($this->option('with-export')){
            $this->export = true;
        }

        if($this->option('without-bulk')){
            $this->withoutBulk = true;
        }
        // TODO test the case, if someone passes a class_name outside Laravel's default App\Http\Controllers folder, if it's going to work

        //TODO check if exists
        //TODO make global for all generator
        //TODO also with prefix
        if(!empty($template = $this->option('template'))) {
            $this->view = 'templates.'.$template.'.controller';
        }

        if(!empty($belongsToMany = $this->option('belongs-to-many'))) {
            $this->setBelongToManyRelation($belongsToMany);
        }

        if ($this->generateClass($force)){

            $this->info('Generating '.$this->classFullName.' finished');

            $icon = "far fa-clone";
            $path = resource_path("js/Layouts/Jig/Menu.json");
            if (file_exists($path)) {
                $menu = collect(json_decode(file_get_contents($path)));
            } else {
                $menu = collect([]);
            }
            if (!$menu->has($this->modelRouteAndViewName)) {
                $item = [
                   "route" => "admin.$this->modelRouteAndViewName.index",
                    "title" => $this->titlePlural,
                    "routePattern" => "admin.$this->modelRouteAndViewName.*",
                    "faIcon" => $icon,
                    "isTitle" => false,
                    "isParent" => false,
                    "children" => []
                ];
                $menu[$this->modelRouteAndViewName] = $item;
                if (file_put_contents($path,json_encode($menu->toArray(), JSON_PRETTY_PRINT))) {
                    $this->info("Updating Sidebar Menu");
                };
            }
        }

    }

    protected function buildClass() : string {

        //Set belongsTo Relations
        $this->setBelongsToRelations();
        return view('jig::'.$this->view, [
            'controllerBaseName' => $this->classBaseName,
            'controllerNamespace' => $this->classNamespace,
            'repoBaseName' => $this->getRepositoryBaseName(),
            "repoFullName" => $this->getRepoNamespace($this->rootNamespace()).'\\'.$this->getRepositoryBaseName(),
            'modelBaseName' => $this->modelBaseName,
            'modelFullName' => $this->modelFullName,
            'modelPlural' => $this->modelPlural,
            'modelTitle' => $this->titleSingular,
            'modelVariableName' => $this->modelVariableName,
            'modelRouteAndViewName' => $this->modelRouteAndViewName,
            'modelViewsDirectory' => $this->modelViewsDirectory,
            'modelDotNotation' => $this->modelDotNotation,
            'modelWithNamespaceFromDefault' => $this->modelWithNamespaceFromDefault,
            'export' => $this->export,
            'withoutBulk' => $this->withoutBulk,
            'exportBaseName' => $this->exportBaseName,
            'resource' => $this->resource,
            'containsPublishedAtColumn' => in_array("published_at", array_column($this->readColumnsFromTable($this->tableName)->toArray(), 'name')),
            // index
            'columnsToQuery' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return !($column['type'] == 'text' || $column['name'] == "password" || $column['name'] == "remember_token" || $column['name'] == "deleted_at"||Str::contains($column['name'],"_id"));
            })->pluck('name')->toArray(),
            'columnsToSearchIn' => $this->readColumnsFromTable($this->tableName)->filter(function($column) {
                return ($column['type'] == 'json' || $column['type'] == 'text' || $column['type'] == 'string' || $column['name'] == "id") && !($column['name'] == "password" || $column['name'] == "remember_token");
            })->pluck('name')->toArray(),
            //            'filters' => $this->readColumnsFromTable($tableName)->filter(function($column) {
            //                return $column['type'] == 'boolean' || $column['type'] == 'date';
            //            }),
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
        return Str::studly(Str::singular($tableName)).'Controller';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace(string $rootNamespace) :string
    {
        return $rootNamespace.'\Http\Controllers\Admin';
    }
    protected function getRepoNamespace($rootNamespace): string
    {
        return $rootNamespace.'Repositories';
    }
    protected function getRepositoryBaseName(): string
    {
        return Str::studly(Str::plural($this->tableName));
    }
}
