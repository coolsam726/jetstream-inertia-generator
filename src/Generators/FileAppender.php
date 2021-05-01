<?php namespace Savannabits\JetstreamInertiaGenerator\Generators;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Savannabits\JetstreamInertiaGenerator\Generators\Traits\Helpers;
use Savannabits\JetstreamInertiaGenerator\Generators\Traits\Names;
use Savannabits\JetstreamInertiaGenerator\Generators\Traits\Columns;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class FileAppender extends Command {

    use Helpers, Columns, Names;

    /**
     * @var Filesystem
     */
    protected Filesystem $files;

    /**
     * Relations
     *
     * @var string
     */
    protected $relations = [];

    /**
     * Create a new controller creator command instance.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    protected function getArguments() {
        return [
            ['table_name', InputArgument::REQUIRED, 'Name of the existing table'],
        ];
    }

    /**
     * Append content to file only if if the content is not present in the file
     *
     * @param $path
     * @param $content
     * @param string $defaultContent content that will be used to populated with newly created file (in case it does not already exists)
     * @return bool
     */
    protected function appendIfNotAlreadyAppended($path, $content, string $defaultContent = "<?php".PHP_EOL.PHP_EOL): bool
    {
        if (!$this->files->exists($path)) {
            $this->makeDirectory($path);
            $this->files->put($path, $defaultContent.$content);
        } else if (!$this->alreadyAppended($path, $content)) {
            $this->files->append($path, $content);
        } else {
            return false;
        }

        return true;
    }

    /**
     * Append content to file only if if the content is not present in the file
     *
     * @param $path
     * @param $search
     * @param $replace
     * @param string $defaultContent content that will be used to populated with newly created file (in case it does not already exists)
     * @return bool
     * @throws FileNotFoundException
     */
    protected function replaceIfNotPresent($path, $search, $replace, string $defaultContent = "<?php".PHP_EOL.PHP_EOL): bool
    {
        if (!$this->files->exists($path)) {
            $this->makeDirectory($path);
            $this->files->put($path, $defaultContent);
        }

        if (!$this->alreadyAppended($path, $replace)) {
            $this->files->put($path, str_replace($search, $replace, $this->files->get($path)));
            return true;
        } else {
            return false;
        }
    }

    /**
     * Execute the console command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initCommonNames($this->argument('table_name'), $this->option('model-name'), $this->option('controller-name'), $this->option('model-with-full-namespace'));

        return parent::execute($input, $output);
    }
}
