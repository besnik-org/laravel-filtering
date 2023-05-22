<?php

namespace Besnik\LaravelInertiaCrud\Actions;

use Besnik\LaravelInertiaCrud\DTO\CrudFieldDto;
use Besnik\LaravelInertiaCrud\Utilities\ActionDeleteSupports;
use Besnik\LaravelInertiaCrud\Utilities\ActionIndexSupports;
use Besnik\LaravelInertiaCrud\Utilities\ActionStoreSupports;
use Besnik\LaravelInertiaCrud\Utilities\ActionUpdateSupports;
use Besnik\LaravelInertiaCrud\Utilities\CrudSupports;
use Besnik\LaravelInertiaCrud\Utilities\DtoSupports;
use Besnik\LaravelInertiaCrud\Utilities\MessageBucket;
use Besnik\LaravelInertiaCrud\Utilities\ModelSupports;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateActions
{
    public function execute(CrudSupports $crudSupports): bool
    {
        $dtoSupport = $crudSupports->dtoSupports();
        $modelSupport = $crudSupports->modelSupports();
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
        ActionIndexSupports $actionIndexSupports,
        ModelSupports $modelSupport,
        CrudSupports $crudSupports
    ): void {

        if (File::exists($actionIndexSupports->fullPath)) {
            MessageBucket::addError("Action {$actionIndexSupports->name} Already exist");
            return;
        }

        $returnModelAlias = Str::camel($crudSupports->name);

        File::put($actionIndexSupports->fullPath, <<<EOT
<?php

namespace {$actionIndexSupports->namespace};

use Exception;
use Illuminate\Http\RedirectResponse;
use {$modelSupport->namespace}\\{$crudSupports->name};

class {$actionIndexSupports->name}
{
    public function execute(): Response
    {
        return Inertia::render('{$crudSupports->name}/Index',
            [
                '{$returnModelAlias}' => {$crudSupports->name}::query()->orderBy('id', 'desc')->paginate(20)
            ]);
    }
}

EOT);

        MessageBucket::addInfo("Action {$actionIndexSupports->name} Created");
  }


    public function createStoreAction(
        ActionStoreSupports $actionStoreSupports,
        ModelSupports $modelSupport,
        CrudSupports $crudSupports,
        DtoSupports $dtoSupport
    ): void {
        // Check if the DTO already exists
        if (File::exists($actionStoreSupports->fullPath)) {
            MessageBucket::addError("Action {$actionStoreSupports->name}  Already exist");
            return;
        }

        $returnModelAlias = Str::camel($crudSupports->name);

        $dtoAlias = Str::camel($dtoSupport->name);
        $storeDependency = "{$dtoSupport->name} \${$dtoAlias}";

        $modelStore = "    \${$returnModelAlias} = new {$crudSupports->name}();\n";
        foreach ($crudSupports->crudDto->fields as $field) {
            /** @var CrudFieldDto $field */
            $modelStore .= "       \${$returnModelAlias}->{$field->name} = \${$dtoAlias}->{$field->name};\n";
        }

        $modelStore .= "       \${$returnModelAlias}->save();\n\n";

        File::put($actionStoreSupports->fullPath, <<<EOT
<?php

namespace {$actionStoreSupports->namespace};

use Exception;
use Illuminate\Http\RedirectResponse;
use {$modelSupport->namespace}\\{$crudSupports->name};
use {$dtoSupport->namespace}\\{$dtoSupport->name};

class {$actionStoreSupports->name}
{
    public function execute({$storeDependency}): RedirectResponse
    {
    
 {$modelStore}
 
       return redirect()->back();
    }
}

EOT);
        MessageBucket::addInfo("Action {$actionStoreSupports->name}  Created");
    }

    public function createUpdateAction(
        ActionUpdateSupports $actionUpdateSupports,
        ModelSupports $modelSupport,
        CrudSupports $crudSupports,
        DtoSupports $dtoSupport
    ): void {
        // Check if the DTO already exists
        if (File::exists($actionUpdateSupports->fullPath)) {
            MessageBucket::addError("Action {$actionUpdateSupports->name}  Already exist");
            return;
        }

        $dtoAlias = Str::camel($dtoSupport->name);
        $returnModelAlias = Str::camel($crudSupports->name);

        $dependency = "{$dtoSupport->name} \${$dtoAlias}";
        $dependency .= ", {$crudSupports->name} \${$returnModelAlias}";

       $modelStore = "";

        foreach ($crudSupports->crudDto->fields as $field) {
            $modelStore .= "       \${$returnModelAlias}->{$field->name} = \${$dtoAlias}->{$field->name};\n";
        }

        $modelStore .= "       \${$returnModelAlias}->update();\n\n";



        File::put($actionUpdateSupports->fullPath, <<<EOT
<?php

namespace {$actionUpdateSupports->namespace};

use Exception;
use Illuminate\Http\RedirectResponse;
use {$modelSupport->namespace}\\{$crudSupports->name};
use {$dtoSupport->namespace}\\{$dtoSupport->name};

class {$actionUpdateSupports->name}
{
    public function execute({$dependency}): RedirectResponse
    {
    
 {$modelStore}
 
       return redirect()->back();
    }
}

EOT);
        MessageBucket::addInfo("Action {$actionUpdateSupports->name}  created");
    }


    public function createDeleteAction(
        ActionDeleteSupports $actionDeleteSupports,
        ModelSupports $modelSupport,
        CrudSupports $crudSupports,
        DtoSupports $dtoSupport
    ): void {

        if (File::exists($actionDeleteSupports->fullPath)) {
            MessageBucket::addError("Action {$actionDeleteSupports->name}  Already exist");
            return;
        }

        $returnModelAlias = Str::camel($crudSupports->name);
        $dependency = "{$crudSupports->name} \${$returnModelAlias}\n";

        File::put($actionDeleteSupports->fullPath, <<<EOT
<?php

namespace App\Actions\Admin\Category;

use Exception;
use Illuminate\Http\RedirectResponse;
use {$modelSupport->namespace}\\{$crudSupports->name};

class {$actionDeleteSupports->name}
{
    public function execute({$dependency}): RedirectResponse
    {
        try {
            \${$returnModelAlias}->delete();
        } catch (Exception \$exception) {
            return redirect()->back()->withErrors(['error' => \$exception->getMessage()]);
        }

        return redirect()->back();
    }
}

EOT);
        MessageBucket::addInfo("Action {$actionDeleteSupports->name}  created");
    }

}