<?php

namespace Besnik\LaravelInertiaCrud\Actions;

use Besnik\LaravelInertiaCrud\Utilities\CrudSupports;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateAction
{
    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Exception
     */
    public function execute(CrudSupports $crudSupports): bool
    {
        $actionSupport = $crudSupports->actionSupports();
        $dtoSupport = $crudSupports->dtoSupports();

        // Check if the DTO already exists
        if (File::exists($actionSupport->fullPath)) {
            throw new Exception("Action Already exist");
        }

        // Create the DTO file dynamically
        $actionCode = "<?php\n\n";
        $actionCode .= "namespace {$actionSupport->namespace};\n\n";
        $actionCode .= "use {$actionSupport->namespace};\n\n";
        /** DTO namespace */
        $actionCode .= "use ".$dtoSupport->namespace.'\\'.$dtoSupport->name.";\n\n";

        $actionCode .= "class {$actionSupport->name} extends DtoAbstraction\n";
        $actionCode .= "{\n";

        $dtoAlias = Str::camel($dtoSupport->name);
        $dependency = ", {$dtoSupport->name} \${$dtoAlias}";

        $actionCode .= "    public function execute(".$dependency."): mixed\n";
        $actionCode .= "    {\n";
        $actionCode .= "        return \${$dtoAlias}->all();\n";
        $actionCode .= "    }\n";
        $actionCode .= "}\n";



        $actionCode .= "\n    public function __construct(\$data = [])\n";
        $actionCode .= "    {\n";
        $actionCode .= "        \$request = request();\n";
        $actionCode .= $dtoAssign;
        $actionCode .= "    }\n";
        $actionCode .= "}\n";

        File::put($actionSupport->fullPath, $actionCode);

        return true;
    }

}