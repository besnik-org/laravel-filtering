<?php

namespace Besnik\LaravelInertiaCrud\Utilities;

use Illuminate\Support\Facades\File;

class ActionIndexSupports
{
    public string $namespace;
    public string $path;
    public string $fullPath;
    public string $name;

    public function __construct(private readonly CrudSupports $crudSupports, public string $type)
    {
        $this->generate();
    }

    public function generate(): void
    {
        $name = $this->crudSupports->name;
        $this->namespace = "App\Actions\Admin".($this->crudSupports->extraNamespace ? '\\'.$this->crudSupports->extraNamespace : '')."\\{$name}";

        $this->path = app_path("Actions/Admin/".($this->crudSupports->extraNamespace ? $this->crudSupports->extraNamespace.'/' : '')."{$name}/");
        $this->name = $this->type.'Action';
        $this->fullPath = $this->path.$this->name.'.php';

        File::ensureDirectoryExists($this->path);
    }

}