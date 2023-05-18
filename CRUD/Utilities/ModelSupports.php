<?php

namespace Besnik\LaravelInertiaCrud\Utilities;

use Besnik\LaravelInertiaCrud\DTO\CrudFieldDto;
use Besnik\LaravelInertiaCrud\Enums\FieldType;
use Illuminate\Support\Facades\File;

class ModelSupports
{
    public string $docblock;
    public string $fillAbleFields;
    public string $namespace;
    public string $path;
    public string $fullPath;

    public function __construct(private readonly CrudSupports $crudSupports)
    {
        $this->generate();
    }

    public function generate(): void
    {
        $this->docblock = "/**\n";
        $this->fillAbleFields = '';
        foreach ($this->crudSupports->crudDto->fields as $field) {
            /** @var  CrudFieldDto $field */

            $type = FieldType::fromPhp($field->type);
            $this->fillAbleFields .= "'{$field->name}', ";

            $this->docblock .= " * @property ".$type.(!$field->required ? '|null' : '')." ".'$'.$field->name."\n";
        }

        $this->docblock .= " */\n";

        $this->namespace = "App\Models".($this->crudSupports->extraNamespace ? '\\'.$this->crudSupports->extraNamespace : '');

        $this->path = app_path("Models/".($this->crudSupports->extraNamespace ? $this->crudSupports->extraNamespace.'/' : ''));
        $this->fullPath = $this->path.$this->crudSupports->name.'.php';

        File::ensureDirectoryExists($this->path);
    }

    public function fillAbleFields(): string
    {
        return trim($this->fillAbleFields, ', ');
    }

}