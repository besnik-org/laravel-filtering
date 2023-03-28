<?php

namespace Besnik\LaravelFiltering;

use Besnik\LaravelFiltering\Filtering\Contracts\FieldContract;
use Besnik\LaravelFiltering\Filtering\Fields\Text\Option;
use Besnik\LaravelFiltering\Filtering\Fields\Text\Text;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use \Illuminate\Database\Query\Builder as DBBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Filter
{

    protected  Builder|DBBuilder|Model $query;
    public  array $fields = [];


    /**
     * @param string $model
     * @return Filter
     */
    public function addModel(string $model): self
    {
        /** @var Model $model */
        $this->query = ($model)::query();

        return $this;
    }

    /**
     * @param Builder|DBBuilder $builder
     * @return Filter
     */
    public function addQuery(Builder|DBBuilder $builder): self
    {
        $this->query = $builder;

        return $this;
    }

    /**
     * @param string $name
     * @param $title
     * @param $function
     * @return Text
     */
    public function textField(string $name, $title = null, $operator = '='): Text
    {
        return $this->fields[$name] = new Text($this->query, $name, $title, $operator);
    }

    /**
     * @param string $name
     * @param $title
     * @param $function
     * @return Option
     */
    public function optionFiled( string $name, $title = null, $operator = '='): Option
    {
        return $this->fields[$name] = new Option( $this->query, $name, $title, $operator);
    }


    public function first(): mixed
    {
      return $this->query->first();
    }

    public function results($isPaginate = true, $paginate = 20): Collection|LengthAwarePaginator|array|string
    {
        $this->applyFields();

        if(!$isPaginate){
          return  $this->query->get();
        }

      return $this->query->toSql($paginate);
    }

    /**
     * @return void
     */
    public function applyFields(): void
    {
        /** @var FieldContract $field */
        foreach ($this->fields as $field){
            $field->apply();
        }

    }
}
