<?php namespace Savannabits\JetstreamInertiaGenerator\Generators;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class Permissions extends ClassGenerator {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'jig:generate:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate permissions migration';

    /**
     * Permissions has also bulk options
     *
     * @return mixed
     */
    protected bool $withoutBulk = false;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $force = $this->option('force');

        if($this->option('without-bulk')){
            $this->withoutBulk = true;
        }

        if ($this->generateClass($force)){
            $this->info('Generating permissions for '.$this->modelBaseName.' finished');
        }
    }

    protected function generateClass($force = false) : bool {
        $fileName = 'fill_permissions_for_'.str_replace("-","_",$this->modelRouteAndViewName).'.php';
        $path = database_path('migrations/'.date('Y_m_d_His', time()).'_'.$fileName);

        if ($oldPath = $this->alreadyExists($fileName)) {
            $path = $oldPath;
            if($force) {
                $this->warn('File '.$path.' already exists! File will be deleted.');
                $this->files->delete($path);
            } else {
                $this->error('File '.$path.' already exists!');
                return false;
            }
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass());
        return true;
    }

    /**
     * Determine if the file already exists.
     *
     * @param $path
     * @return bool
     */
    protected function alreadyExists($path): bool
    {
        foreach ($this->files->files(database_path('migrations')) as $file) {
            if(str_contains($file->getFilename(), $path)) {
                return $file->getPathname();
            }
        }
        return false;
    }

    /**
     * @return string
     */
    protected function buildClass() : string {

        return view('jig::permissions', [
            'modelBaseName' => $this->modelBaseName,
            'modelPlural' => $this->modelPlural,
            'titlePlural' => $this->titlePlural,
            'titleSingular' => $this->titleSingular,
            'modelPermissionName' => $this->modelRouteAndViewName,
            'modelDotNotation' => $this->modelDotNotation,
            'className' => $this->generateClassNameFromTable($this->tableName),
            'withoutBulk' => $this->withoutBulk,
        ])->render();
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Generates a code for the given model'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating request'],
            ['without-bulk', 'wb', InputOption::VALUE_NONE, 'Generate without bulk options'],
        ];
    }

    public function generateClassNameFromTable($tableName): string
    {
        return 'FillPermissionsFor'.Str::plural($this->modelBaseName);
    }
}
