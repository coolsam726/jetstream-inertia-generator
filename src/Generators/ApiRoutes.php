<?php namespace Savannabits\JetstreamInertiaGenerator\Generators;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ApiRoutes extends FileAppender {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jig:generate:api:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Append api routes to the api.php routes file';

    /**
     * Path for view
     *
     * @var string
     */
    protected string $view = 'api-routes';

    /**
     * Routes have also export route
     *
     * @return mixed
     */
    protected bool $export = false;

    /**
     * Routes have also bulk options route
     *
     * @return mixed
     */
    protected bool $withoutBulk = false;

    public function handle()
    {
        if($this->option('with-export')){
            $this->export = true;
        }

        if($this->option('without-bulk')){
            $this->withoutBulk = true;
        }

        //TODO check if exists
        //TODO make global for all generator
        //TODO also with prefix
        if(!empty($template = $this->option('template'))) {
            $this->view = 'templates.'.$template.'.api-routes';
        }

        if ($this->appendIfNotAlreadyAppended(base_path('routes/api.php'), PHP_EOL.PHP_EOL.$this->buildClass())){
            $this->info('Appending API routes finished');
        }
    }

    protected function buildClass(bool $api=false): string
    {
        return view('jig::'.$this->view, [
            'controllerClassName' => class_basename($this->controllerWithNamespaceFromDefault),
            'controllerPartiallyFullName' => $this->controllerWithNamespaceFromDefault,
            'modelRouteName' => Str::plural($this->modelRouteAndViewName),
            'modelVariableName' => $this->modelVariableName,
            'modelViewsDirectory' => $this->modelViewsDirectory,
            'resource' => $this->resource,
            'export' => $this->export,
            'withoutBulk' => true,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a controller for the given model'],
            ['controller-name', 'c', InputOption::VALUE_OPTIONAL, 'Specify custom controller name'],
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom template'],
            ['with-export', 'e', InputOption::VALUE_NONE, 'Generate an option to Export as Excel'],
            ['without-bulk', 'wb', InputOption::VALUE_NONE, 'Generate without bulk options'],
        ];
    }

}
