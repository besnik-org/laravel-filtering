<?php

namespace Besnik\LaravelInertiaCrud\Actions;

use Besnik\LaravelInertiaCrud\Utilities\CrudSupports;
use Illuminate\Support\Facades\File;

class CreateModel
{
    /**
     * @throws \Exception
     */
    public function execute(CrudSupports $crudSupports): bool
    {
        $modelSupport =  $crudSupports->modelSupports();

        // Check if the model already exists
        if (File::exists($modelSupport->fullPath)) {
            throw new \Exception("Model Already exist");
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

        return true;
    }
}