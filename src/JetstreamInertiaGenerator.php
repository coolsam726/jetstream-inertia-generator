<?php

namespace Savannabits\JetstreamInertiaGenerator;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class JetstreamInertiaGenerator extends Command
{
    protected $name = "jig:generate";
    protected $description ="Scaffold a whole CRUD module";
    protected $files;

    public function handle(Filesystem $files) {
        $this->files = $files;

        $tableNameArgument = $this->argument('table_name');
        $modelOption = $this->option('model-name');
        $controllerOption = $this->option('controller-name');
        $repositoryOption = $this->option('repository-name');
        $policyOption = $this->option('policy-name');
        $exportOption = $this->option('with-export');
        $withoutBulkOptions = $this->option('without-bulk');
        $force = $this->option('force');
        $template = $this->option('template');

        $this->call('jig:generate:model', [
            'table_name' => $tableNameArgument,
            'class_name' => $modelOption,
            '--force' => $force,
            '--template' => $template,
        ]);

        /*$this->call('jig:generate:factory', [
            'table_name' => $tableNameArgument,
            '--model-name' => $modelOption,
            '--seed' => $this->option('seed'),
        ]);*/

        $this->call('jig:generate:request:index', [
            'table_name' => $tableNameArgument,
            '--model-name' => $modelOption,
            '--force' => $force,
            '--template' => $template,
        ]);

        $this->call('jig:generate:request:store', [
            'table_name' => $tableNameArgument,
            '--model-name' => $modelOption,
            '--force' => $force,
            '--template' => $template,
        ]);

        $this->call('jig:generate:request:update', [
            'table_name' => $tableNameArgument,
            '--model-name' => $modelOption,
            '--force' => $force,
            '--template' => $template,
        ]);

        $this->call('jig:generate:request:destroy', [
            'table_name' => $tableNameArgument,
            '--model-name' => $modelOption,
            '--force' => $force,
        ]);

        $this->call('jig:generate:repository', [
            'table_name' => $tableNameArgument,
            'class_name' => $repositoryOption,
            '--model-name' => $modelOption,
            '--force' => $force,
            '--with-export' => $exportOption,
            '--without-bulk' => $withoutBulkOptions,
            '--template' => $template,
        ]);

        $this->call('jig:generate:api:controller', [
            'table_name' => $tableNameArgument,
            'class_name' => $controllerOption,
            '--model-name' => $modelOption,
            '--force' => $force,
            '--with-export' => $exportOption,
            '--without-bulk' => $withoutBulkOptions,
            '--template' => $template,
        ]);
        $this->call('jig:generate:controller', [
            'table_name' => $tableNameArgument,
            'class_name' => $controllerOption,
            '--model-name' => $modelOption,
            '--force' => $force,
            '--with-export' => $exportOption,
            '--without-bulk' => $withoutBulkOptions,
            '--template' => $template,
        ]);


         $this->call('jig:generate:routes', [
            'table_name' => $tableNameArgument,
            '--model-name' => $modelOption,
            '--controller-name' => $controllerOption,
            '--with-export' => $exportOption,
            '--without-bulk' => $withoutBulkOptions,
             '--template' => $template,
        ]);


        $this->call('jig:generate:api:routes', [
            'table_name' => $tableNameArgument,
            '--model-name' => $modelOption,
            '--controller-name' => $controllerOption,
            '--with-export' => $exportOption,
            '--without-bulk' => $withoutBulkOptions,
            '--template' => $template,
        ]);

        $this->call('jig:generate:index', [
            'table_name' => $tableNameArgument,
            '--model-name' => $modelOption,
            '--force' => $force,
            '--with-export' => $exportOption,
            '--without-bulk' => $withoutBulkOptions,
            '--template' => $template,
        ]);

        $this->call('jig:generate:form', [
            'table_name' => $tableNameArgument,
            '--model-name' => $modelOption,
            '--force' => $force,
            '--template' => $template,
        ]);

        /*
        $this->call('jig:generate:lang', [
            'table_name' => $tableNameArgument,
            '--model-name' => $modelOption,
            '--with-export' => $exportOption,
        ]);


        if($exportOption){
            $this->call('jig:generate:export', [
                'table_name' => $tableNameArgument,
                '--force' => $force,
            ]);
        }

        */
        $this->call('jig:generate:policy', [
            'table_name' => $tableNameArgument,
            'class_name' => $policyOption,
            '--model-name' => $modelOption,
            '--force' => $force,
            '--with-export' => false,
            '--without-bulk' => false,
            '--template' => $template,
        ]);

        if ($this->shouldGeneratePermissionsMigration()) {
            $this->call('jig:generate:permissions', [
                'table_name' => $tableNameArgument,
                '--model-name' => $modelOption,
                '--force' => $force,
                '--without-bulk' => $withoutBulkOptions,
            ]);

            if ($this->option('no-interaction') || $this->confirm('Do you want to attach generated permissions to the default role now?', true)) {
                $this->call('migrate');
            }
        }

        $this->info('Generating whole admin CRUD module finished');
    }

    protected function getArguments() {
        return [
            ['table_name', InputArgument::REQUIRED, 'Name of the existing table'],
        ];
    }

    protected function getOptions() {
        return [
            ['template', 't', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
            ['model-name', 'm', InputOption::VALUE_OPTIONAL, 'Specify custom model name'],
            ['controller-name', 'c', InputOption::VALUE_OPTIONAL, 'Specify custom controller name'],
            ['repository-name', 'r', InputOption::VALUE_OPTIONAL, 'Specify custom repository class name'],
            ['policy-name', 'p', InputOption::VALUE_OPTIONAL, 'Specify custom Policy class name'],
            ['seed', 's', InputOption::VALUE_NONE, 'Seeds the table with fake data'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force will delete files before regenerating admin'],
            ['with-export', 'e', InputOption::VALUE_NONE, 'Generate an option to Export as Excel'],
            ['without-bulk', 'wb', InputOption::VALUE_NONE, 'Generate without bulk options'],
        ];
    }

    protected function shouldGeneratePermissionsMigration() {
        if (class_exists('\Savannabits\JetstreamInertiaGenerator\JetstreamInertiaGenerator')) {
            return true;
        }

        return false;
    }
}
