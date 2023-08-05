<?php

namespace Besnik\LaravelFiltering\Filter\Fields;

use Besnik\LaravelFiltering\Filter\Conditions\TextCondition;

class FieldContract
{
    protected array $conditions;
    protected string $type = '';

    public function __construct(
        protected string $name,
        protected string|null $title = null,
        protected string $operator = '=',
    ) {
        if (!$this->title) {
            $this->title = ucwords(str_replace('_', ' ', $this->name));
        }

        $this->conditions = TextCondition::equal();
    }

    public  function conditions(...$conditions): self
    {
        if(empty($conditions)){
            $this->conditions = match ($this->type){
                'text' => TextCondition::all(),
                default => []
            };
        }

        if(count($conditions) == 1){
            $this->conditions = is_string(array_key_first($conditions)) ? $conditions : $conditions[0];
            return $this;
        }

        foreach (func_get_args() as $key => $condition){
            if(!is_string($key)){
                $k = array_key_first($condition);
                $this->conditions[$k] = $condition[$k];
                continue;
            }

            $this->conditions[$key] = $condition;
        }

        return $this;
    }

    public  function getConditions(): array
    {
        return $this->conditions;
    }

    private function getData(): array
    {
      return  [
          'name' => $this->name,
          'title' => $this->title,
          'conditions' => $this->conditions,
        ];
    }

    public function __call($method, $args)
    {
        return match ($method) {
            'getData' => $this->getData(),
        };
    }
}
