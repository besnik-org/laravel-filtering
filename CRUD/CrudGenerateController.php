<?php
declare(strict_types=1);

namespace Besnik\LaravelInertiaCrud;

use App\Http\Controllers\Controller;
use Besnik\LaravelInertiaCrud\Actions\CreateActionForIndex;
use Besnik\LaravelInertiaCrud\Actions\CreateController;
use Besnik\LaravelInertiaCrud\Actions\CreateDto;
use Besnik\LaravelInertiaCrud\Actions\CreateMigration;
use Besnik\LaravelInertiaCrud\Actions\CreateModel;
use Besnik\LaravelInertiaCrud\Actions\CreateRequest;
use Besnik\LaravelInertiaCrud\DTO\CrudDto;
use Besnik\LaravelInertiaCrud\Utilities\CrudSupports;
use Exception;
use Illuminate\View\View;

class CrudGenerateController extends Controller
{
    public function __construct(
        private readonly CreateModel $createModel,
        private readonly CreateMigration $createMigration,
        private readonly CreateRequest $createRequest,
        private readonly CreateController $createController,
        private readonly CreateDto $createDto,
        private readonly CreateActionForIndex $createActionForIndex,
    ) {}

    public function index(): View
    {
        return view('inertiaCrudView::crud');
    }

    public function generate(CrudDto $crudDto): mixed
    {
        try {

            $crudSupports = new CrudSupports($crudDto);
            if ($crudDto->createModel) {
                $this->createModel->execute($crudSupports);
            }

            if ($crudDto->createMigration) {
                $this->createMigration->execute($crudSupports);
            }

           $this->createRequest->execute($crudSupports);

           $this->createDto->execute($crudSupports);

          $this->createActionForIndex->execute($crudSupports);

          $this->createController->execute($crudSupports);


        } catch (Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }

        return true;
    }
}
