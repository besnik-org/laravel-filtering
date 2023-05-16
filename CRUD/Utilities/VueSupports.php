<?php

namespace Besnik\LaravelInertiaCrud\Utilities;

use Illuminate\Support\Facades\File;

class VueSupports
{
    public string $path;
    public string $fullPath;
    public string $indexVuePath;
    public string $supportJSPath;
    public string $createModalPath;
    public string $updateModalPath;

    public function __construct(private readonly CrudSupports $crudSupports)
    {
        $this->generate();
    }

    public function generate(): void
    {
        $this->path = base_path("resources/js/Admin/".($this->crudSupports->extraNamespace ? $this->crudSupports->extraNamespace.'/' : '').$this->crudSupports->name.'/');
        $this->indexVuePath = $this->path.'Index.vue';
        $this->createModalPath = $this->path.'/'.'CreateModal.vue';
        $this->updateModalPath = $this->path.'/'.'UpdateModal.vue';
        $this->supportJSPath = $this->path.'/'.'Support.js';

        File::ensureDirectoryExists($this->path);
    }


}