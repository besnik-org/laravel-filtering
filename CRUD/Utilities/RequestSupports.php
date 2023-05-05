<?php

namespace Besnik\LaravelInertiaCrud\Utilities;

use Besnik\LaravelInertiaCrud\DTO\CrudFieldDto;
use Illuminate\Support\Facades\File;

class RequestSupports
{
    public string $namespace;
    public string $path;
    public string $fullPath;
    public string $name;

    public function __construct(private readonly CrudSupports $crudSupports)
    {
        $this->generate();
    }

    public function generate(): void
    {
        $this->namespace = "App\Http\Requests".($this->crudSupports->extraNamespace ? '\\'.$this->crudSupports->extraNamespace : '');

        $this->path = app_path("Http/Requests/".($this->crudSupports->extraNamespace ? $this->crudSupports->extraNamespace.'/' : ''));
        $this->name = $this->crudSupports->name.'Request';
        $this->fullPath = $this->path.$this->name.'.php';

        File::ensureDirectoryExists($this->path);
    }


    public function getRules(): string
    {
        $data = '';
        foreach ($this->crudSupports->crudDto->fields as $field) {
            /** @var CrudFieldDto $field */

            $validationRules = trim(trim($field->validationRules), '|');
            $rules = $field->required ? "'required', " : "'nullable', ";
            foreach (explode('|', $validationRules) as $rule) {
                $rules .= "'$rule', ";
            }

            $rules = trim($rules, ', ');

            $data .= "            '{$field->name}' => [{$rules}],\n";
        }

        return $data;
    }
}