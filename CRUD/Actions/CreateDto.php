<?php

namespace Besnik\LaravelInertiaCrud\Actions;

use Besnik\LaravelInertiaCrud\DTO\CrudFieldDto;
use Besnik\LaravelInertiaCrud\Enums\FieldType;
use Besnik\LaravelInertiaCrud\Utilities\CrudSupports;
use Besnik\LaravelInertiaCrud\Utilities\MessageBucket;
use Illuminate\Support\Facades\File;

class CreateDto
{
    public function execute(CrudSupports $crudSupports): bool
    {
        $this->createDtoContract();

        $dtoSupport = $crudSupports->dtoSupports();

        // Check if the DTO already exists
        if (File::exists($dtoSupport->fullPath)) {
            MessageBucket::addError("Dto {$dtoSupport->name} Already exist");
            return false;
        }

        // Create the DTO file dynamically
        $dtoCode = "<?php\n\n";
        $dtoCode .= "namespace {$dtoSupport->namespace};\n\n";
        $dtoCode .= "use App\DTO\Admin\DtoAbstraction;\n\n";
        $dtoCode .= "class {$dtoSupport->name} extends DtoAbstraction\n";
        $dtoCode .= "{\n";
        $dtoAssign = '';
        foreach ($crudSupports->crudDto->fields as $field) {
            /** @var CrudFieldDto $field */

            $type = FieldType::fromPhp($field->type);
            $dtoCode .= "    public {$type} \${$field->name};\n";

            $dtoAssign .= "        \$this->{$field->name} = \$data['{$field->name}'] ?? \$request->input('{$field->name}');\n";
        }


        $dtoCode .= "\n    public function __construct(\$data = [])\n";
        $dtoCode .= "    {\n";
        $dtoCode .= "        \$request = request();\n";
        $dtoCode .= $dtoAssign;
        $dtoCode .= "    }\n";
        $dtoCode .= "}\n";

        File::put($dtoSupport->fullPath, $dtoCode);

        MessageBucket::addError("Dto {$dtoSupport->name} Created");

        return true;
    }

    public function createDtoContract(): void
    {
        $filePath = app_path('DTO/DtoAbstraction.php');

        File::ensureDirectoryExists(app_path('DTO/Admin'));

        if (File::exists($filePath)) {
            return;
        }

        $fileContent = "<?php\n\n";
        $fileContent .= "namespace App\DTO;\n\n";
        $fileContent .= "abstract class DtoAbstraction\n";
        $fileContent .= "{\n";
        $fileContent .= "    public function all(): array\n";
        $fileContent .= "    {\n";
        $fileContent .= "        return get_object_vars(\$this);\n";
        $fileContent .= "    }\n";
        $fileContent .= "}\n";

        File::put($filePath, $fileContent);
    }
}