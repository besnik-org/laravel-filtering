<?php

namespace Besnik\LaravelInertiaCrud\DTO;

class CrudFieldDto extends DtoAbstraction
{
    public string $name;
    public string $type;
    public bool $required;
    public string $validationRules;

    public function __construct($data = [])
    {
        $request = request();
        $this->name = $data['name'] ?? $request->input('name') ;
        $this->type = $data['type'] ?? $request->input('type') ;
        $this->required = $data['required'] ?? ($request->input('required') ?? false );
        $this->validationRules = $data['validationRules'] ?? ($request->input('validationRules') ?? '' );
    }
}