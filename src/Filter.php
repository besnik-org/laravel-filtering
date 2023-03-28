<?php declare(strict_types=1);

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
    public array $fields = [];

    protected Builder|DBBuilder|Model $query;

    public function addModel(string $model): self
    {
        /** @var Model $model */
        $this->query = ($model)::query();

        return $this;
    }

    public function addQuery(Builder|DBBuilder $builder): self
    {
        $this->query = $builder;

        return $this;
    }

    /**
     * @param $function
     */
    public function textField(string $name, $title = null, $operator = '='): Text
    {
        return $this->fields[$name] = new Text($this->query, $name, $title, $operator);
    }

    /**
     * @param $function
     */
    public function optionFiled(string $name, $title = null, $operator = '='): Option
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

        if (! $isPaginate) {
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
}
