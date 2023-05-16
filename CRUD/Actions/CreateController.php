<?php

namespace Besnik\LaravelInertiaCrud\Actions;

use Besnik\LaravelInertiaCrud\Utilities\ActionCreateSupports;
use Besnik\LaravelInertiaCrud\Utilities\CrudSupports;
use Besnik\LaravelInertiaCrud\Utilities\MessageBucket;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateController
{

    public function execute(CrudSupports $crudSupports): bool
    {
        $controllerSupport = $crudSupports->controllerSupports();
        $requestSupport = $crudSupports->requestSupports();
        $dtoSupport = $crudSupports->dtoSupports();
        $modelSupport = $crudSupports->modelSupports();

        $actionIndexSupports = $crudSupports->actionIndexSupports('Index');
        $actionStoreSupports = $crudSupports->actionStoreSupports('Store');
        $actionUpdateSupports = $crudSupports->actionUpdateSupports('Update');
        $actionDeleteSupports = $crudSupports->actionDeleteSupports('Delete');


        if (File::exists($controllerSupport->fullPath)) {
            MessageBucket::addError("Controller `{$controllerSupport->name}` Already exist");
            return true;
        }

        $requestCode = "<?php\n\n";

        $requestCode .= "namespace {$controllerSupport->namespace};\n\n";

        $requestCode .= "use App\Http\Controllers\Controller;\n";
        $requestCode .= "use Illuminate\Http\RedirectResponse;\n";
        $requestCode .= "use Inertia\Inertia;\n";
        /** Request namespace */
        $requestCode .= "use ".$requestSupport->namespace.'\\'.$requestSupport->name.";\n";

        /** Model namespace */
        $requestCode .= "use ".$modelSupport->namespace.'\\'.$crudSupports->name.";\n";

        /** DTO namespace */
        $requestCode .= "use ".$dtoSupport->namespace.'\\'.$dtoSupport->name.";\n";

        /** Action Index namespace */
        $requestCode .= "use ".$actionIndexSupports->namespace.'\\'.$actionIndexSupports->name.";\n";

        /** Action Store namespace */
        $requestCode .= "use ".$actionStoreSupports->namespace.'\\'.$actionStoreSupports->name.";\n";

        /** Action Update namespace */
        $requestCode .= "use ".$actionUpdateSupports->namespace.'\\'.$actionUpdateSupports->name.";\n";

        /** Action Delete namespace */
        $requestCode .= "use ".$actionDeleteSupports->namespace.'\\'.$actionDeleteSupports->name.";\n\n";

        $requestCode .= "class {$controllerSupport->name} extends Controller\n";
        $requestCode .= "{\n";

        $requestAlias = Str::camel($requestSupport->name);
        $dtoAlias = Str::camel($dtoSupport->name);

        // Index Method

        $actionIndexAlias = Str::camel($actionIndexSupports->name);
        $requestCode .= "    public function index({$actionIndexSupports->name} \${$actionIndexAlias}): \Inertia\Response\n";
        $requestCode .= "    {\n";
        $requestCode .= "        return Inertia::render('Admin/{$crudSupports->name}', \${$actionIndexAlias}->execute());\n";
        $requestCode .= "    }\n\n";

        // store method
        $actionStoreAlias = Str::camel($actionStoreSupports->name);

        $storeDependency = "{$dtoSupport->name} \${$dtoAlias}";
        $storeDependency .= ", {$requestSupport->name} \${$requestAlias}";
        $storeDependency .= ", {$actionStoreSupports->name} \${$actionStoreAlias}";

        $requestCode .= "    public function store(".$storeDependency."): RedirectResponse\n";
        $requestCode .= "    {\n";
        $requestCode .= "        return \${$actionStoreAlias}->execute(\${$dtoAlias});\n";
        $requestCode .= "    }\n\n";


        // store method
        $actionUpdateAlias = Str::camel($actionUpdateSupports->name);

        $modelAlias = Str::camel($crudSupports->name);

        $updateDependency = "{$dtoSupport->name} \${$dtoAlias}";
        $updateDependency .= ", {$requestSupport->name} \${$requestAlias}";
        $updateDependency .= ", {$actionUpdateSupports->name} \${$actionUpdateAlias}";
        $updateDependency .= ", {$crudSupports->name} \${$modelAlias}";

        $requestCode .= "    public function update(".$updateDependency."): RedirectResponse\n";
        $requestCode .= "    {\n";
        $requestCode .= "        return \${$actionUpdateAlias}->execute(\${$dtoAlias}, \${$modelAlias}, );\n";
        $requestCode .= "    }\n\n";

        // delete method
        $actionDeleteAlias = Str::camel($actionDeleteSupports->name);

        $deleteDependency = "{$crudSupports->name} \${$modelAlias}";
        $deleteDependency .= ", {$actionDeleteSupports->name} \${$actionDeleteAlias}";

        $requestCode .= "    public function destroy(".$deleteDependency."): RedirectResponse\n";
        $requestCode .= "    {\n";
        $requestCode .= "        return \${$actionDeleteAlias}->execute(\${$modelAlias});\n";
        $requestCode .= "    }\n\n";
        $requestCode .= "}";

        File::put($controllerSupport->fullPath, $requestCode);

        MessageBucket::addInfo("Controller {$controllerSupport->name} Created");
        return true;
    }


}