<?php

namespace Besnik\LaravelInertiaCrud\DTO;

use Illuminate\Support\Str;

class CrudDto extends DtoAbstraction
{
    public string $name;
    public bool $createModel;
    public bool $createMigration;
    public bool $createSeeder;
    public bool $createController;
    public bool $createDto;
    public bool $createValidator;
    public bool $createAction;
    public bool $createInertiaTable;
    public bool $createInertiaCreateModal;
    public bool $createInertiaUpdateModal;
    public bool $createInertiaDeletePrompt;
    public array $fields;
    public string $route;

    public function __construct($data = [])
    {
        $request = request();
        $this->name = Str::studly(str_replace(' ', '', $request->input('name')));
        $this->createModel = $request->input('model');
        $this->createMigration = $request->input('migration');
        $this->createSeeder = $request->input('seeder');
        $this->createController = $request->input('controller');
        $this->createDto = $request->input('dto');
        $this->createValidator = $request->input('validator');
        $this->createAction = $request->input('action');
        $this->createInertiaTable = $request->input('table');
        $this->createInertiaCreateModal = $request->input('create_modal');
        $this->createInertiaUpdateModal = $request->input('update_modal');
        $this->createInertiaDeletePrompt = $request->input('delete_prompt');
        $this->route = $request->input('route');
        $this->fields = [];
        foreach ($request->input('fields') ?? [] as $field) {
            $field['type'] = $field['type']['id'];
            $this->fields[] = new CrudFieldDto([
                ...$field
            ]);
        }
    }

}