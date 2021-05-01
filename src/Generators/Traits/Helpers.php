<?php namespace Savannabits\JetstreamInertiaGenerator\Generators\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait Helpers {

    public function option($key = null) {
        return ($key === null || $this->hasOption($key)) ? parent::option($key) : null;
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
     * @return string
     */
    protected function makeDirectory(string $path): string
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    /**
     * Determine if the file already exists.
     *
     * @param $path
     * @return bool
     */
    protected function alreadyExists($path): bool
    {
        return $this->files->exists($path);
    }


    /**
     * Check if provided relation has a table
     *
     * @param $relationTable
     * @return mixed
     */
    public function checkRelationTable($relationTable)
    {
        return Schema::hasTable($relationTable);
    }

    /**
     * sets Relation of Belongs To Many type
     *
     * @param $belongsToMany
     * @return mixed
     */
    //TODO add other relation types
    public function setBelongToManyRelation($belongsToMany)
    {
        $this->relations['belongsToMany'] = collect(explode(',', $belongsToMany))->filter(function($belongToManyRelation) {
            return $this->checkRelationTable($belongToManyRelation);
        })->map(function($belongsToMany) {
            return [
                'current_table' => $this->tableName,
                'related_table' => $belongsToMany,
                'related_model' => ($belongsToMany == 'roles') ? "Spatie\\Permission\\Models\\Role" : "App\\Models\\". Str::studly(Str::singular($belongsToMany)),
                'related_model_class' => ($belongsToMany == 'roles') ? "Spatie\\Permission\\Models\\Role::class" : "App\\Models\\". Str::studly(Str::singular($belongsToMany)).'::class',
                'related_model_name' => Str::studly(Str::singular($belongsToMany)),
                'related_model_name_plural' => Str::studly($belongsToMany),
                'related_model_variable_name' => lcfirst(Str::singular(class_basename($belongsToMany))),
                'relation_table' => trim(collect([$this->tableName, $belongsToMany])->sortBy(function($table) {
                    return $table;
                })->reduce(function($relationTable, $table) {
                    return $relationTable.'_'.$table;
                }), '_'),
                'foreign_key' => Str::singular($this->tableName).'_id',
                'related_key' => Str::singular($belongsToMany).'_id',
            ];
        })->keyBy('related_table');
    }


    /**
     * Determine if the content is already present in the file
     *
     * @param $path
     * @param $content
     * @return bool
     */
    protected function alreadyAppended($path, $content): bool
    {
        if (str_contains($this->files->get($path), $content)) {
            return true;
        }
        return false;
    }

}
