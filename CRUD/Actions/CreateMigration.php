<?php

namespace Besnik\LaravelInertiaCrud\Actions;

use Besnik\LaravelInertiaCrud\Utilities\CrudSupports;
use Illuminate\Support\Facades\File;

class CreateMigration
{

    /**
     * @throws \Exception
     */
    public function execute(CrudSupports $crudSupports): bool
    {
        $migrationSupport =  $crudSupports->migrationSupports();
        $timestamp = now()->format('Y_m_d_His');

        $migrationFileName = "{$timestamp}_create_{$crudSupports->tableName}_table.php";

        $migrationCode = "<?php\n\n";
        $migrationCode .= "use Illuminate\Database\Migrations\Migration;\n";
        $migrationCode .= "use Illuminate\Database\Schema\Blueprint;\n";
        $migrationCode .= "use Illuminate\Support\Facades\Schema;\n\n";
        $migrationCode .= "return new class () extends Migration {\n";
        $migrationCode .= "    /**\n";
        $migrationCode .= "     * Run the migrations.\n";
        $migrationCode .= "     */\n";
        $migrationCode .= "    public function up()\n";
        $migrationCode .= "    {\n";
        $migrationCode .= "        Schema::create('{$crudSupports->tableName}', function (Blueprint \$table) {\n";

        $migrationCode .= $migrationSupport->fieldsString();

        $migrationCode .= "            \$table->timestamps();\n";
        $migrationCode .= "        });\n";
        $migrationCode .= "    }\n\n";
        $migrationCode .= "    public function down()\n";
        $migrationCode .= "    {\n";
        $migrationCode .= "        Schema::dropIfExists('{$crudSupports->tableName}');\n";
        $migrationCode .= "    }\n";
        $migrationCode .= "};\n";

        File::put(database_path("migrations/{$migrationFileName}"), $migrationCode);

        return true;
    }
}