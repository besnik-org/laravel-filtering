<?php

namespace Besnik\LaravelInertiaCrud\Actions;

use Besnik\LaravelInertiaCrud\Utilities\CrudSupports;
use Exception;
use Illuminate\Support\Facades\File;

class CreateRequest
{
    /**
     * @throws \Exception
     */
    public function execute(CrudSupports $crudSupports): bool
    {
        $requestSupport = $crudSupports->requestSupports();

        if (File::exists($requestSupport->fullPath)) {
            throw new Exception("Request Already exist");
        }

        $requestCode = "<?php\n\n";
        $requestCode .= "namespace {$requestSupport->namespace};\n\n";
        $requestCode .= "use Illuminate\Foundation\Http\FormRequest;\n";
        $requestCode .= "use Illuminate\Validation\Rule;\n\n";
        $requestCode .= "class {$requestSupport->name} extends FormRequest\n";
        $requestCode .= "{\n";
        $requestCode .= "    public function rules(): array\n";
        $requestCode .= "    {\n";
        $requestCode .= "        return [\n";

        $requestCode .= $requestSupport->getRules();

        $requestCode .= "        ];\n";
        $requestCode .= "    }\n";
        $requestCode .= "}\n";

        File::put($requestSupport->fullPath, $requestCode);

        return true;
    }

}