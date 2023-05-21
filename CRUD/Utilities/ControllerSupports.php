<?php

namespace Besnik\LaravelInertiaCrud\Utilities;

use Illuminate\Support\Facades\File;

class ControllerSupports
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
        $this->namespace = "App\Http\Controllers\Admin".($this->crudSupports->extraNamespace ? '\\'.$this->crudSupports->extraNamespace : '');

        $this->path = app_path("Http/Controllers/Admin/".($this->crudSupports->extraNamespace ? $this->crudSupports->extraNamespace.'/' : ''));
        $this->name = $this->crudSupports->name.'Controller';
        $this->fullPath = $this->path.$this->name.'.php';

        File::ensureDirectoryExists($this->path);
    }

}