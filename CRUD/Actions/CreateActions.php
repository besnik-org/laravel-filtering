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
// Check if the DTO already exists
        if (File::exists($actionIndexSupports->fullPath)) {
            MessageBucket::addError("Action {$actionIndexSupports->name} Already exist");
            return;
        }

        $actionCode = "<?php\n\n";
        $actionCode .= "namespace {$actionIndexSupports->namespace};\n\n";
        $actionCode .= "use {$modelSupport->namespace}\\{$crudSupports->name};\n\n";

        $actionCode .= "class {$actionIndexSupports->name}\n";
        $actionCode .= "{\n";

        $returnModelAlias = Str::camel($crudSupports->name);

        $actionCode .= "    public function execute(): array\n";
        $actionCode .= "    {\n";
        $actionCode .= "        return [ \n";
        $actionCode .= "           '{$returnModelAlias}' => {$crudSupports->name}::query()->paginate(20)\n";
        $actionCode .= "         ];\n";
        $actionCode .= "    }\n";
        $actionCode .= "}\n";

        File::put($actionIndexSupports->fullPath, $actionCode);

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

        $actionCode = "<?php\n\n";
        $actionCode .= "namespace {$actionStoreSupports->namespace};\n\n";
        $actionCode .= "use {$modelSupport->namespace}\\{$crudSupports->name};\n";
        $actionCode .= "use {$dtoSupport->namespace}\\{$dtoSupport->name};\n\n";

        $actionCode .= "class {$actionStoreSupports->name}\n";
        $actionCode .= "{\n";

        $returnModelAlias = Str::camel($crudSupports->name);

        $dtoAlias = Str::camel($dtoSupport->name);
        $storeDependency = "{$dtoSupport->name} \${$dtoAlias}";

        $actionCode .= "    public function execute({$storeDependency})\n";
        $actionCode .= "    {\n";
        $actionCode .= "    \${$returnModelAlias} = new {$crudSupports->name}();\n";
        foreach ($crudSupports->crudDto->fields as $field) {
            /** @var CrudFieldDto $field */
            $actionCode .= "    \${$returnModelAlias}->{$field->name} = \${$dtoAlias}->{$field->name};\n";
        }

        $actionCode .= "    \${$returnModelAlias}->save();\n";
        $actionCode .= "    return \${$returnModelAlias};\n";
        $actionCode .= "    }\n";
        $actionCode .= "}\n";

        File::put($actionStoreSupports->fullPath, $actionCode);

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

        foreach ($crudSupports->crudDto->fields as $field) {
            /** @var CrudFieldDto $field */
            $actionCode .= "    \${$returnModelAlias}->{$field->name} = \${$dtoAlias}->{$field->name};\n";
        }

        $actionCode .= "    \${$returnModelAlias}->update();\n";
        $actionCode .= "    return \${$returnModelAlias};\n";
        $actionCode .= "    }\n";
        $actionCode .= "}\n";

        File::put($actionUpdateSupports->fullPath, $actionCode);

        MessageBucket::addInfo("Action {$actionUpdateSupports->name}  created");
    }


    public function createDeleteAction(
        ActionDeleteSupports $actionDeleteSupports,
        ModelSupports $modelSupport,
        CrudSupports $crudSupports,
        DtoSupports $dtoSupport
    ): void {
        // Check if the DTO already exists
        if (File::exists($actionDeleteSupports->fullPath)) {
            MessageBucket::addError("Action {$actionDeleteSupports->name}  Already exist");
            return;
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

        MessageBucket::addInfo("Action {$actionDeleteSupports->name}  created");
    }

}