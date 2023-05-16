<?php

declare(strict_types=1);

namespace Besnik\LaravelInertiaCrud;

use App\Http\Controllers\Controller;
use Besnik\LaravelInertiaCrud\Actions\CreateActions;
use Besnik\LaravelInertiaCrud\Actions\CreateController;
use Besnik\LaravelInertiaCrud\Actions\CreateDto;
use Besnik\LaravelInertiaCrud\Actions\CreateMigration;
use Besnik\LaravelInertiaCrud\Actions\CreateModel;
use Besnik\LaravelInertiaCrud\Actions\CreateRequest;
use Besnik\LaravelInertiaCrud\Actions\CreateVue;
use Besnik\LaravelInertiaCrud\DTO\CrudDto;
use Besnik\LaravelInertiaCrud\Enums\FieldType;
use Besnik\LaravelInertiaCrud\Utilities\CrudSupports;
use Illuminate\View\View;

class CrudGenerateController extends Controller
{
    public function __construct(
        private readonly CreateModel $createModel,
        private readonly CreateMigration $createMigration,
        private readonly CreateRequest $createRequest,
        private readonly CreateController $createController,
        private readonly CreateDto $createDto,
        private readonly CreateActions $createActions,
        private readonly CreateVue $createVue,
    ) {}

    public function index(): View
    {
        $fieldTypes = [];
        foreach (FieldType::all() as $key => $value) {
            $fieldTypes[] = [
                "id" => $key,
                "name" => $key,
            ];
        }

        return view('inertiaCrudView::crud', [
            'fieldTypes' => $fieldTypes
        ]);
    }

    public function generate(CrudDto $crudDto): mixed
    {
        $crudSupports = new CrudSupports($crudDto);
        if ($crudDto->createModel) {
            $this->createModel->execute($crudSupports);
        }

        if ($crudDto->createMigration) {
            $this->createMigration->execute($crudSupports);
        }

        if ($crudDto->createValidator) {
            $this->createRequest->execute($crudSupports);
        }

        if ($crudDto->createDto) {
            $this->createDto->execute($crudSupports);
        }

        if ($crudDto->createAction) {
            $this->createActions->execute($crudSupports);
        }

        if ($crudDto->createController) {
            $this->createController->execute($crudSupports);
        }

        $this->createVue->execute($crudSupports);


        return true;
    }
}
