<?php

declare(strict_types=1);

namespace Besnik\LaravelFiltering;

use Besnik\LaravelFiltering\Filtering\Contracts\FieldContract;
use Besnik\LaravelFiltering\Filtering\Fields\Text\Option;
use Besnik\LaravelFiltering\Filtering\Fields\Text\Text;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as DBBuilder;

class Filter
{
    private array $fields = [];

    public Builder|DBBuilder|Model $query;

    public function __construct(Builder|DBBuilder|Model|string $model)
    {
        $this->setModel($model);
    }


    private function setModel(Builder|DBBuilder|Model|string $model): void
    {
        if (is_string($model)) {
            $this->query =   $model::query();
            return;
        }

        if ($model instanceof Model) {
            $this->query = ($model)->query();
            return;
        }

        $this->query = $model;
    }

    public function text(string $name, $title = null, $operator = '='): Text
    {
        return $this->fields[$name] = new Text($this->query, $name, $title, $operator);
    }

    public function option(string $name, $title = null, $operator = '='): Option
    {
        return $this->fields[$name] = new Option($this->query, $name, $title, $operator);
    }

    public function first(): mixed
    {
        return $this->query->first();
    }

    public function results($isPaginate = true, $paginate = 20): Collection|LengthAwarePaginator|array|string
    {
        $this->applyFields();

        if (!$isPaginate) {
            return $this->query->get();
        }

        return $this->query->toSql($paginate);
    }

    public function applyFields(): void
    {
        /** @var FieldContract $field */
        foreach ($this->fields as $field) {
            $field->apply();
        }
    }

    public function getFields($isPaginate = true, $paginate = 20): array
    {
        $fields = [];
        /** @var FieldContract $field */
        foreach ($this->fields as $key => $field) {
            $fields[$key] = $field->fields();
        }

        return $fields;
    }
}
