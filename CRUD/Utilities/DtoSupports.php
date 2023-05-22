<?php

namespace Besnik\LaravelInertiaCrud\Utilities;

use Illuminate\Support\Facades\File;

class DtoSupports
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
        $this->namespace = "App\DTO\Admin".($this->crudSupports->extraNamespace ? '\\'.$this->crudSupports->extraNamespace : '');

        $this->path = app_path("DTO/Admin/".($this->crudSupports->extraNamespace ? $this->crudSupports->extraNamespace.'/' : ''));
        $this->name = $this->crudSupports->name.'Dto';
        $this->fullPath = $this->path.$this->name.'.php';

        File::ensureDirectoryExists($this->path);
    }

}