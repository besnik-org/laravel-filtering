<?php

namespace Besnik\LaravelFiltering\Filtering\Fields\Text;

use Besnik\LaravelFiltering\Filtering\Contracts\FieldContract;
use Besnik\LaravelFiltering\Filtering\Enums\Condition;

class Text extends FieldContract
{
    protected string $type = 'text';

    public function notEqual(string $title = 'Not Equal'): self
    {
        $this->data['conditions'][Condition::NOT_EQUAL->value] = ['title' => $title];

        return $this;
    }

    public function startWith(string $title = 'Start With'): self
    {
        $this->data['conditions'][Condition::START_WITH->value] = ['title' => $title];

        return $this;
    }

    public function endWith(string $title = 'End With'): self
    {
        $this->data['conditions'][Condition::END_WITH->value] = ['title' => $title ];

        return $this;
    }

    public function in(array $options = [], string $separatedBy = ',', ?string $title = 'In'): self
    {
        $this->data[Condition::IN->value] = [
            'title' => $title ,
            'separated_by' => $separatedBy,
            'options' => $options
        ];

        return $this;
    }

    protected function default()
    {
        $this->equal();
    }

    public function equal(string $title = 'Equal'): self
    {
        $this->data['conditions'][Condition::EQUAL->value] = ['title' => $title];

        return $this;
    }

    public function contain(string $title = 'Contain'): self
    {
        $this->data['conditions'][Condition::EQUAL->value] = ['title' => $title];

        return $this;
    }

    protected function setQueryWithCondition(): void
    {

        $conditionName = $this->conditionField();
        $conditionValue = request()->input($conditionName);
        $value = trim(request()->input($this->name));

        $this->callback = match ($conditionValue) {
            Condition::EQUAL->value => function () use ($value) {
                $this->query->where($this->name, '=', $value);
            },
            Condition::NOT_EQUAL->value => function () use ($value) {
                $this->query->where($this->name, '!=', $value);
            },
            Condition::START_WITH->value => function () use ($value) {
                $this->query->where($this->name, 'like', $value.'%');
            },
            Condition::END_WITH->value => function () use ($value) {
                $this->query->where($this->name, 'like', '%'.$value);
            },
            Condition::CONTAIN->value => function () use ($value) {
                $this->query->where($this->name, 'like', '%'.$value.'%');
            },
            Condition::IN->value => function () use ($value) {
                $separatedBy = $this->data[Condition::IN->value]['separated_by'] ?? ',';
                $this->query->whereIn($this->name, explode($separatedBy, $value));
            },
            default => function () use ($value) {
                $this->query->where($this->name, '=', $value);
            }
        };


    }
}
