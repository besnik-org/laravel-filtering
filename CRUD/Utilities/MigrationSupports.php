<?php

namespace Besnik\LaravelInertiaCrud\Utilities;

use Besnik\LaravelInertiaCrud\DTO\CrudFieldDto;
use Besnik\LaravelInertiaCrud\Enums\FieldType;
use Illuminate\Support\Str;

class MigrationSupports
{

    public function __construct(private readonly CrudSupports $crudSupports) {}

    public function fieldsString(): string
    {
        $migrationCode = '';

        foreach ($this->crudSupports->crudDto->fields as $field) {
            /** @var $field CrudFieldDto */
            $type = FieldType::from($field->type);
            if ($type === 'foreignId') {
                $table = str_replace('_id', '', $field->name);
                $tableName = Str::plural($table);
                $migrationCode .= "            \$table->{$type}('{$field->name}')".(!$field->required ? '->nullable()' : '')."->constrained('{$tableName}');\n";
            } else {
                $migrationCode .= "            \$table->{$type}('{$field->name}')".(!$field->required ? '->nullable()' : '').";\n";
            }
        }

        return $migrationCode;
    }

}