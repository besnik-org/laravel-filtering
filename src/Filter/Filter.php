<?php

namespace Besnik\LaravelFiltering\Filter;

use Besnik\LaravelFiltering\Filter\Fields\FieldContract;
use Besnik\LaravelFiltering\Filter\Fields\Text;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as DBBuilder;

abstract class Filter
{
    private array $fields= [];
    private Builder|DBBuilder $query;

    protected abstract function model(): Builder|DBBuilder|Model|string;
    protected abstract function fields(): array;

    public function text(string $name, $title = null, $operator = '='): Text
    {
        return $this->fields[$name] = new Text($name, $title, $operator);
    }

    public function getQuery()
    {
        /** @var Builder|DBBuilder|Model|string $model */
        $model =  $this->model();

        if (is_string($model)) {
            $this->query =   $model::query();
        }

        if ($model instanceof Model) {
           $this->query = ($model)->query();
        }

        return  $this->query;
    }

    public function getFields()
    {
        $this->fields();

        $data = [];
        foreach ($this->fields as $field){
            /** @var FieldContract $field */
            $data[] = $field->getData();
        }

        return $data;
    }
}
