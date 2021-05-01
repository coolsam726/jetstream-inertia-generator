<?php namespace Savannabits\JetstreamInertiaGenerator\Generators\Traits;

use Savannabits\JetstreamInertiaGenerator\Generators\Controller;
use Savannabits\JetstreamInertiaGenerator\Generators\Model;
use Illuminate\Support\Str;

trait Names
{

    public $tableName;
    public $modelBaseName;
    public $modelFullName;
    public $modelPlural;
    public $modelVariableName;
    public $modelRouteAndViewName;
    public $modelNamespace;
    public $modelWithNamespaceFromDefault;
    public $modelViewsDirectory;
    public $modelDotNotation;
    public $modelJSName;
    public $modelLangFormat;
    public $resource;
    public $exportBaseName;
    public $titleSingular;
    public $titlePlural;

    public $controllerWithNamespaceFromDefault;

    protected function initCommonNames(
        $tableName,
        $modelName = null,
        $controllerName = null,
        $modelWithFullNamespace = null
    ) {
        $this->tableName = $tableName;

        if ($this instanceof Model) {
            $modelGenerator = $this;
        } else {
            $modelGenerator = app(Model::class);
            $modelGenerator->setLaravel($this->laravel);
        }

        if (is_null($modelName)) {
            $modelName = $modelGenerator->generateClassNameFromTable($this->tableName);
        }
        $this->modelFullName = $modelGenerator->qualifyClass($modelName);

        $this->modelBaseName = class_basename($modelName);
        $this->modelPlural = Str::plural(class_basename($modelName));
        $this->modelVariableName = lcfirst(Str::singular(class_basename($this->modelBaseName)));
        $this->modelRouteAndViewName = Str::plural(Str::lower(Str::kebab($this->modelBaseName)));
        $this->modelNamespace = Str::replaceLast("\\" . $this->modelBaseName, '', $this->modelFullName);
        if (!Str::startsWith($this->modelFullName,
            $startsWith = trim($modelGenerator->rootNamespace(), '\\') . '\Models\\')) {
            $this->modelWithNamespaceFromDefault = $this->modelBaseName;
        } else {
            $this->modelWithNamespaceFromDefault = Str::replaceFirst($startsWith, '', $this->modelFullName);
        }
        $this->modelViewsDirectory = Str::plural(Str::lower(Str::kebab(implode('/',
            collect(explode('\\', $this->modelWithNamespaceFromDefault))->map(function ($part) {
                return lcfirst($part);
            })->toArray()))));

        $parts = collect(explode('\\', $this->modelWithNamespaceFromDefault));
        $parts->pop();
        $parts->push($this->modelPlural);
        $this->resource = Str::lower(Str::kebab(implode('', $parts->toArray())));

        $this->modelDotNotation = str_replace('/', '.', $this->modelViewsDirectory);
        $this->modelJSName = Str::plural(str_replace('/', '-', $this->modelViewsDirectory));
        $this->modelLangFormat = str_replace('/', '_', $this->modelViewsDirectory);

        if ($this instanceof Controller) {
            $controllerGenerator = $this;
        } else {
            $controllerGenerator = app(Controller::class);
            $controllerGenerator->setLaravel($this->laravel);
        }

        if (is_null($controllerName)) {
            $controllerName = $controllerGenerator->generateClassNameFromTable($this->tableName);
        }

        $controllerFullName = $controllerGenerator->qualifyClass($controllerName);
        if (!Str::startsWith($controllerFullName,
            $startsWith = trim($controllerGenerator->rootNamespace(), '\\') . '\Http\\Controllers\\Admin\\')
            && !Str::startsWith($controllerFullName,
                $startsWith = trim($controllerGenerator->rootNamespace(), '\\') . '\Http\\Controllers\\Api\\' )) {
            $this->controllerWithNamespaceFromDefault = $controllerFullName;
        } else {
            $this->controllerWithNamespaceFromDefault = Str::replaceFirst($startsWith, '', $controllerFullName);
        }

        if (!empty($modelWithFullNamespace)) {
            $this->modelFullName = $modelWithFullNamespace;
        }
        $this->exportBaseName = Str::studly($tableName) . 'Export';

        $this->titleSingular = Str::singular(str_replace(['_'], ' ', Str::title($this->tableName)));
        $this->titlePlural = str_replace(['_'], ' ', Str::title($this->tableName));
    }

    public function valueWithoutId($string)
    {
        if (Str::endsWith(Str::lower($string), '_id')) {
            $string = Str::substr($string, 0, -3);
        }

        return Str::ucfirst(str_replace('_', ' ', $string));
    }

}
