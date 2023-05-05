<?php

namespace Besnik\LaravelInertiaCrud\Actions;

use Besnik\LaravelInertiaCrud\Utilities\CrudSupports;
use Besnik\LaravelInertiaCrud\Utilities\DtoSupports;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateActionForIndex
{
    /**
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Exception
     */
    public function execute(CrudSupports $crudSupports): bool
    {
        $modelSupport = $crudSupports->modelSupports();
        $dtoSupport = $crudSupports->dtoSupports();
        $modelSupport = $crudSupports->modelSupports();
        $requestSupport = $crudSupports->requestSupports();
        $actionIndexSupports = $crudSupports->actionIndexSupports('Index');
        $actionStoreSupports = $crudSupports->actionStoreSupports('Store');
        $actionUpdateSupports = $crudSupports->actionUpdateSupports('Update');
        $actionDeleteSupports = $crudSupports->actionDeleteSupports('Delete');

        $this->createIndexAction($actionIndexSupports, $modelSupport, $crudSupports);
        $this->createStoreAction($actionStoreSupports, $modelSupport, $crudSupports, $dtoSupport);
        $this->createUpdateAction($actionUpdateSupports, $modelSupport, $crudSupports, $dtoSupport);
        $this->createDeleteAction($actionDeleteSupports, $modelSupport, $crudSupports, $dtoSupport);


        return true;
    }

    public function createIndexAction(
        \Besnik\LaravelInertiaCrud\Utilities\ActionIndexSupports $actionIndexSupports,
        \Besnik\LaravelInertiaCrud\Utilities\ModelSupports $modelSupport,
        CrudSupports $crudSupports
    ): void {
// Check if the DTO already exists
        if (File::exists($actionIndexSupports->fullPath)) {
            throw new Exception("Action Index Already exist");
        }

        $actionCode = "<?php\n\n";
        $actionCode .= "namespace {$actionIndexSupports->namespace};\n\n";
        $actionCode .= "use {$modelSupport->namespace}\\{$crudSupports->name};\n\n";

        $actionCode .= "class {$actionIndexSupports->name}\n";
        $actionCode .= "{\n";

        $returnModelAlias = Str::camel($crudSupports->name);;

        $actionCode .= "    public function execute(): array\n";
        $actionCode .= "    {\n";
        $actionCode .= "        return [ \n";
        $actionCode .= "           '{$returnModelAlias}' => {$crudSupports->name}::query()->paginate(20)\n";
        $actionCode .= "         ];\n";
        $actionCode .= "    }\n";
        $actionCode .= "}\n";

        File::put($actionIndexSupports->fullPath, $actionCode);
    }


    public function createStoreAction(
        \Besnik\LaravelInertiaCrud\Utilities\ActionStoreSupports $actionStoreSupports,
        \Besnik\LaravelInertiaCrud\Utilities\ModelSupports $modelSupport,
        CrudSupports $crudSupports,
        DtoSupports $dtoSupport
    ): void {
        // Check if the DTO already exists
        if (File::exists($actionStoreSupports->fullPath)) {
            throw new Exception("Action Index Already exist");
        }

        $actionCode = "<?php\n\n";
        $actionCode .= "namespace {$actionStoreSupports->namespace};\n\n";
        $actionCode .= "use {$modelSupport->namespace}\\{$crudSupports->name};\n";
        $actionCode .= "use {$dtoSupport->namespace}\\{$dtoSupport->name};\n\n";

        $actionCode .= "class {$actionStoreSupports->name}\n";
        $actionCode .= "{\n";

        $returnModelAlias = Str::camel($crudSupports->name);;

        $dtoAlias = Str::camel($dtoSupport->name);
        $storeDependency = "{$dtoSupport->name} \${$dtoAlias}";

        $actionCode .= "    public function execute({$storeDependency})\n";
        $actionCode .= "    {\n";

        $actionCode .= "    }\n";
        $actionCode .= "}\n";

        File::put($actionStoreSupports->fullPath, $actionCode);
    }

    public function createUpdateAction(
        \Besnik\LaravelInertiaCrud\Utilities\ActionUpdateSupports $actionUpdateSupports,
        \Besnik\LaravelInertiaCrud\Utilities\ModelSupports $modelSupport,
        CrudSupports $crudSupports,
        DtoSupports $dtoSupport
    ): void {
        // Check if the DTO already exists
        if (File::exists($actionUpdateSupports->fullPath)) {
            throw new Exception("Action Index Already exist");
        }

        $actionCode = "<?php\n\n";
        $actionCode .= "namespace {$actionUpdateSupports->namespace};\n\n";
        $actionCode .= "use {$modelSupport->namespace}\\{$crudSupports->name};\n";
        $actionCode .= "use {$dtoSupport->namespace}\\{$dtoSupport->name};\n\n";

        $actionCode .= "class {$actionUpdateSupports->name}\n";
        $actionCode .= "{\n";

        $dtoAlias = Str::camel($dtoSupport->name);
        $returnModelAlias = Str::camel($crudSupports->name);

        $dependency = "{$dtoSupport->name} \${$dtoAlias}";
        $dependency .= ", {$crudSupports->name} \${$returnModelAlias}";

        $actionCode .= "    public function execute({$dependency})\n";
        $actionCode .= "    {\n";

        $actionCode .= "    }\n";
        $actionCode .= "}\n";

        File::put($actionUpdateSupports->fullPath, $actionCode);
    }


    public function createDeleteAction(
        \Besnik\LaravelInertiaCrud\Utilities\ActionDeleteSupports $actionDeleteSupports,
        \Besnik\LaravelInertiaCrud\Utilities\ModelSupports $modelSupport,
        CrudSupports $crudSupports,
        DtoSupports $dtoSupport
    ): void {

        // Check if the DTO already exists
        if (File::exists($actionDeleteSupports->fullPath)) {
            throw new Exception("Action Delete Already exist");
        }

        $actionCode = "<?php\n\n";
        $actionCode .= "namespace {$actionDeleteSupports->namespace};\n\n";
        $actionCode .= "use {$modelSupport->namespace}\\{$crudSupports->name};\n";

        $actionCode .= "class {$actionDeleteSupports->name}\n";
        $actionCode .= "{\n";

        $returnModelAlias = Str::camel($crudSupports->name);
        $dependency = "{$crudSupports->name} \${$returnModelAlias}";

        $actionCode .= "    public function execute({$dependency})\n";
        $actionCode .= "    {\n";

        $actionCode .= "    }\n";
        $actionCode .= "}\n";

        File::put($actionDeleteSupports->fullPath, $actionCode);
    }

}