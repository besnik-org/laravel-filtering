<?php

namespace Besnik\LaravelInertiaCrud\DTO;

abstract class DtoAbstraction
{
    public function all(): array
    {
        return get_object_vars($this);
    }
}