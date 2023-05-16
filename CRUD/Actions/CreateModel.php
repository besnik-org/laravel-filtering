<?php

namespace Besnik\LaravelInertiaCrud\Actions;

use Besnik\LaravelInertiaCrud\Utilities\CrudSupports;
use Besnik\LaravelInertiaCrud\Utilities\MessageBucket;
use Illuminate\Support\Facades\File;

class CreateModel
{

    public function execute(CrudSupports $crudSupports): bool
    {
        $modelSupport = $crudSupports->modelSupports();

        // Check if the model already exists
        if (File::exists($modelSupport->fullPath)) {
            MessageBucket::addError("Model ` {$crudSupports->name}` Already exist");
            return false;
        }

        $modelCode = "<?php\n\n";
        $modelCode .= "namespace ".$modelSupport->namespace.";\n\n";
        $modelCode .= "use Illuminate\Database\Eloquent\Factories\HasFactory;\n";
        $modelCode .= "use Illuminate\Database\Eloquent\Model;\n\n";

        $modelCode .= $modelSupport->docblock."class {$crudSupports->name} extends Model\n";
        $modelCode .= "{\n";
        $modelCode .= "    use HasFactory;\n\n";
        $modelCode .= "    protected \$table = '{$crudSupports->tableName}';\n\n";
        $modelCode .= "    protected \$fillable = [{$modelSupport->fillAbleFields()}];\n\n";
        $modelCode .= "    protected \$casts = [\n";
        $modelCode .= "    ];\n";
        $modelCode .= "}\n";

        File::put($modelSupport->fullPath, $modelCode);
        MessageBucket::addInfo("Model ` {$crudSupports->name}` Created");
        return true;
    }
}