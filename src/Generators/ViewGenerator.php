<?php namespace Savannabits\JetstreamInertiaGenerator\Generators;

use Savannabits\JetstreamInertiaGenerator\Generators\Traits\Helpers;
use Savannabits\JetstreamInertiaGenerator\Generators\Traits\Names;
use Savannabits\JetstreamInertiaGenerator\Generators\Traits\Columns;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class ViewGenerator extends Command {

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
            // FIXME add OPTIONAL file_name argument
        ];
    }


    /**
     * Append content to file only if if the content is not present in the file
     *
     * @param $path
     * @param $content
     * @return bool
     */
    protected function appendIfNotAlreadyAppended($path, $content): bool
    {
        if (!$this->files->exists($path)) {
            $this->makeDirectory($path);
            $this->files->put($path, $content);
        } else if (!$this->alreadyAppended($path, $content)) {
            $this->files->append($path, $content);
        } else {
            return false;
        }

        return true;

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
        $this->initCommonNames($this->argument('table_name'), $this->option('model-name'));

        return parent::execute($input, $output);
    }

}
