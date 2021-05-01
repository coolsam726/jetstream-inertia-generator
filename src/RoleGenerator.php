<?php

namespace Savannabits\JetstreamInertiaGenerator;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RoleGenerator extends Command
{
    protected $name = "jig:generate:role";
    protected $description ="Scaffold a roles CRUD module";
    protected Filesystem $files;

    public function handle(Filesystem $files) {
        $this->files = $files;

        $tableNameArgument = $this->argument('table_name') ?? 'roles';
        $force = $this->option('force');

        $this->call('jig:generate', [
            'table_name' => $tableNameArgument,
            '--force' => $force,
            '--template' => 'role',
        ]);

    }

    protected function getArguments() {
        return [
            ['table_name', InputArgument::OPTIONAL, 'Name of the existing table'],
        ];
    }

    protected function getOptions() {
        return [
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating admin'],
        ];
    }
}
