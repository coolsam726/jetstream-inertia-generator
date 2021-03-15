<?php namespace Savannabits\JetstreamInertiaGenerator\Generators\Traits;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Schema;

trait Columns {

    /**
     * @param $tableName
     * @return Collection
     */
    protected function readColumnsFromTable($tableName) {

        // TODO how to process jsonb & json translatable columns? need to figure it out

        $indexes = collect(\Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes($tableName));
        return collect(Schema::getColumnListing($tableName))->map(function($columnName) use ($tableName, $indexes) {

            //Checked unique index
            $columnUniqueIndexes = $indexes->filter(function($index) use ($columnName) {
                return in_array($columnName, $index->getColumns()) && ($index->isUnique() && !$index->isPrimary());
            });
            $columnPrimaryIndex = $indexes->filter(function($index) use ($columnName) {
                return in_array($columnName,$index->getColumns()) && $index->isPrimary();
            });
            $columnUniqueDeleteAtCondition = $columnUniqueIndexes->filter(function($index) {
                return $index->hasOption('where') ? $index->getOption('where') == '(deleted_at IS NULL)' : false;
            });

            // TODO add foreign key

            return [
                'name' => $columnName,
                'primary' => $columnPrimaryIndex->count() > 0,
                'label' => Str::title(str_replace("-"," ",Str::slug($columnName))),
                'type' => Schema::getColumnType($tableName, $columnName),
                'required' => boolval(Schema::getConnection()->getDoctrineColumn($tableName, $columnName)->getNotnull()),
                'unique' => $columnUniqueIndexes->count() > 0,
                'unique_deleted_at_condition' => $columnUniqueDeleteAtCondition->count() > 0,
            ];
        })
            /*->sortByDesc(function ($item) {return $item['type']==='json';})
            ->sortByDesc(function ($item) {return $item['type']==='longText';})
            ->sortByDesc(function ($item) {return $item['type']==='time';})
            ->sortByDesc(function ($item) {return $item['type']==='tinyinteger';})
            ->sortByDesc(function ($item) {return $item['type']==='bigInteger';})
            ->sortByDesc(function ($item) {return $item['type']==='mediumInteger';})
            ->sortByDesc(function ($item) {return $item['type']==='integer';})
            ->sortByDesc(function ($item) {return $item['type']==='float';})
            ->sortByDesc(function ($item) {return $item['type']==='double';})
            ->sortByDesc(function ($item) {return $item['type']==='text';})
            ->sortByDesc(function ($item) {return $item['name']==='amount';})*/
            ->sortByDesc(function ($item) {return $item['type']==='boolean';})
            ->sortByDesc(function ($item) {return $item['type']==='datetime'&& !in_array($item['name'],['created_at','updated_at']);})
            ->sortByDesc(function ($item) {return $item['type']==='date';})
            ->sortByDesc(function ($item) {return $item['type']==='float';})
//            ->sortByDesc(function ($item) {return $item['type']==='double';})
            ->sortByDesc(function ($item) {return $item['type']==='text' && !in_array($item['name'],["description",'details','content','body','text']);})
            ->sortByDesc(function ($item) {return $item['type']==='string' && !in_array($item['name'],["description",'details','content','body','text','middle_name','other_names','first_name','last_name','surname','title','headline','name','display_name','slug']);})
            ->sortByDesc(function ($item) {return in_array($item['name'],["description",'details','content','body','text']);})
            ->sortByDesc(function ($item) {return in_array($item['name'],['middle_name','other_names']);})
            ->sortByDesc(function ($item) {return in_array($item['name'],['first_name','last_name','surname']);})
            ->sortByDesc(function ($item) {return in_array($item['name'],['headline']);})
            ->sortByDesc(function ($item) {return in_array($item['name'],['display_name']);})
            ->sortByDesc(function ($item) {return in_array($item['name'],['title']);})
            ->sortByDesc(function ($item) {return in_array($item['name'],['name']);})
            ->sortByDesc(function ($item) {return in_array($item['name'],['slug']);})
            ->sortByDesc(function ($item) {return in_array($item['name'],['id']);})
            ->values()
            ;
    }

    protected function getVisibleColumns($tableName, $modelVariableName) {
        $columns = $this->readColumnsFromTable($tableName);
        $hasSoftDelete = ($columns->filter(function($column) {
                return $column['name'] == "deleted_at";
            })->count() > 0);
        return $columns->filter(function($column) {
            return !($column['name'] == "id" || $column['name'] == "created_at" || $column['name'] == "updated_at" || $column['name'] == "deleted_at" || $column['name'] == "remember_token");
        })->map(function($column) use ($tableName, $hasSoftDelete, $modelVariableName){
            $serverStoreRules = collect([]);
            $serverUpdateRules = collect([]);
            $frontendRules = collect([]);
            if ($column['required']) {
                $serverStoreRules->push('\'required\'');
                $serverUpdateRules->push('\'sometimes\'');
                if($column['type'] != 'boolean' && $column['name'] != 'password') {
                    $frontendRules->push('required');
                }
            } else {
                $serverStoreRules->push('\'nullable\'');
                $serverUpdateRules->push('\'nullable\'');
            }

            if ($column['name'] == 'email') {
                $serverStoreRules->push('\'email\'');
                $serverUpdateRules->push('\'email\'');
                $frontendRules->push('email');
            }

            if ($column['name'] == 'password') {
                $serverStoreRules->push('\'confirmed\'');
                $serverUpdateRules->push('\'confirmed\'');
                $frontendRules->push('confirmed:password');

                $serverStoreRules->push('\'min:7\'');
                $serverUpdateRules->push('\'min:7\'');
                $frontendRules->push('min:7');

                $serverStoreRules->push('\'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/\'');
                $serverUpdateRules->push('\'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/\'');
                //TODO not working, need fixing
//                $frontendRules->push(''regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!$#%]).*$/g'');
            }

            if($column['unique'] || $column['name'] == 'slug') {
                if($column['type'] == 'json') {
                    $storeRule = 'Rule::unique(\''.$tableName.'\', \''.$column['name'].'->\'.$locale)';
                    $updateRule = 'Rule::unique(\''.$tableName.'\', \''.$column['name'].'->\'.$locale)->ignore($this->'.$modelVariableName.'->getKey(), $this->'.$modelVariableName.'->getKeyName())';
                    if($hasSoftDelete && $column['unique_deleted_at_condition']) {
                        $storeRule .= '->whereNull(\'deleted_at\')';
                        $updateRule .= '->whereNull(\'deleted_at\')';
                    }
                    $serverStoreRules->push($storeRule);
                    $serverUpdateRules->push($updateRule);
                } else {
                    $storeRule = 'Rule::unique(\''.$tableName.'\', \''.$column['name'].'\')';
                    $updateRule = 'Rule::unique(\''.$tableName.'\', \''.$column['name'].'\')->ignore($this->'.$modelVariableName.'->getKey(), $this->'.$modelVariableName.'->getKeyName())';
                    if($hasSoftDelete && $column['unique_deleted_at_condition']) {
                        $storeRule .= '->whereNull(\'deleted_at\')';
                        $updateRule .= '->whereNull(\'deleted_at\')';
                    }
                    $serverStoreRules->push($storeRule);
                    $serverUpdateRules->push($updateRule);
                }
            }

            switch ($column['type']) {
                case 'datetime':
                    $serverStoreRules->push('\'date\'');
                    $serverUpdateRules->push('\'date\'');
                    $frontendRules->push('date_format:yyyy-MM-dd HH:mm:ss');
                    break;
                case 'date':
                    $serverStoreRules->push('\'date\'');
                    $serverUpdateRules->push('\'date\'');
                    $frontendRules->push('date_format:yyyy-MM-dd');
                    break;
                case 'time':
                    $serverStoreRules->push('\'date_format:H:i:s\'');
                    $serverUpdateRules->push('\'date_format:H:i:s\'');
                    $frontendRules->push('date_format:HH:mm:ss');
                    break;

                case 'integer':
                    $serverStoreRules->push('\'integer\'');
                    $serverUpdateRules->push('\'integer\'');
                    $frontendRules->push('integer');
                    break;
                case 'tinyInteger':
                    $serverStoreRules->push('\'integer\'');
                    $serverUpdateRules->push('\'integer\'');
                    $frontendRules->push('integer');
                    break;
                case 'smallInteger':
                    $serverStoreRules->push('\'integer\'');
                    $serverUpdateRules->push('\'integer\'');
                    $frontendRules->push('integer');
                    break;
                case 'mediumInteger':
                    $serverStoreRules->push('\'integer\'');
                    $serverUpdateRules->push('\'integer\'');
                    $frontendRules->push('integer');
                    break;
                case 'bigInteger':
                    $serverStoreRules->push('\'integer\'');
                    $serverUpdateRules->push('\'integer\'');
                    $frontendRules->push('integer');
                    break;
                case 'bigint':
                    $serverStoreRules->push('\'integer\'');
                    $serverUpdateRules->push('\'integer\'');
                    $frontendRules->push('integer');
                    break;
                case 'unsignedInteger':
                    $serverStoreRules->push('\'integer\'');
                    $serverUpdateRules->push('\'integer\'');
                    $frontendRules->push('integer');
                    break;
                case 'unsignedTinyInteger':
                    $serverStoreRules->push('\'integer\'');
                    $serverUpdateRules->push('\'integer\'');
                    $frontendRules->push('integer');
                    break;
                case 'unsignedSmallInteger':
                    $serverStoreRules->push('\'integer\'');
                    $serverUpdateRules->push('\'integer\'');
                    $frontendRules->push('integer');
                    break;
                case 'unsignedMediumInteger':
                    $serverStoreRules->push('\'integer\'');
                    $serverUpdateRules->push('\'integer\'');
                    $frontendRules->push('integer');
                    break;
                case 'unsignedBigInteger':
                    $serverStoreRules->push('\'integer\'');
                    $serverUpdateRules->push('\'integer\'');
                    $frontendRules->push('integer');
                    break;

                case 'boolean':
                    $serverStoreRules->push('\'boolean\'');
                    $serverUpdateRules->push('\'boolean\'');
                    $frontendRules->push('');
                    break;
                case 'float':
                    $serverStoreRules->push('\'numeric\'');
                    $serverUpdateRules->push('\'numeric\'');
                    $frontendRules->push('decimal');
                    break;
                case 'decimal':
                    $serverStoreRules->push('\'numeric\'');
                    $serverUpdateRules->push('\'numeric\'');
                    $frontendRules->push('decimal'); // FIXME?? I'm not sure about this one
                    break;
                case 'string':
                    $serverStoreRules->push('\'string\'');
                    $serverUpdateRules->push('\'string\'');
                    break;
                case 'text':
                    $serverStoreRules->push('\'string\'');
                    $serverUpdateRules->push('\'string\'');
                    break;
                default:
                    $serverStoreRules->push('\'string\'');
                    $serverUpdateRules->push('\'string\'');
            }

            return [
                'name' => $column['name'],
                'type' => $column['type'],
                'label' => Str::title(str_replace("-"," ",Str::slug($column["name"]))),
                'serverStoreRules' => $serverStoreRules->toArray(),
                'serverUpdateRules' => $serverUpdateRules->toArray(),
                'frontendRules' => $frontendRules->toArray(),
            ];
        });
    }

    protected function setBelongsToRelations() {
        $relationships = collect(app('db')->connection()->getDoctrineSchemaManager()->listTableForeignKeys($this->tableName))->map(function($fk) {
            /**@var ForeignKeyConstraint $fk*/
            $columns = $this->readColumnsFromTable($fk->getForeignTableName())->filter(function($column) {
                return in_array($column["name"],["name", "display_name","title"]) || in_array($column["type"],["string"]);
            })->pluck("name");
            $labelColumn = $columns->filter(function($column){return in_array($column, [
                    'name','display_name', 'title'
                ]);
            })->first();
            if (!$labelColumn) $labelColumn = $columns->filter(function($column){return $column==='title';})->first();
            if (!$labelColumn) $labelColumn = $columns->filter(function($column){return $column==='name';})->first();
            if (!$labelColumn) $labelColumn = $columns->first();
            if (!$labelColumn) $labelColumn = "id";
            $functionName = collect($fk->getColumns())->first();
            if (str_contains($functionName,"_id")) {$functionName = Str::singular(str_replace("_id","",$functionName));} else {
                $functionName =Str::singular($functionName)."_model";
            }
            $functionName = Str::camel($functionName);
            $relatedTitle = Str::title(str_replace("_"," ",Str::snake(Str::studly($functionName))));
            return [
                "function_name" => $functionName,
                "related_table" => $fk->getForeignTableName(),
                "related_route_name" => Str::slug(Str::pluralStudly($fk->getForeignTableName())),
                "related_model" => "\\$this->modelNamespace\\". Str::studly(Str::singular($fk->getForeignTableName())).'::class',
                "related_model_title" => $relatedTitle,
                "label_column" => $labelColumn,
                "relationship_variable" => Str::snake($functionName),
                "foreign_key" => collect($fk->getColumns())->first(),
                "owner_key" => collect($fk->getForeignColumns())->first(),
            ];
        })->keyBy('foreign_key');
        $this->relations["belongsTo"] = $relationships;
        return $relationships;
    }

}
