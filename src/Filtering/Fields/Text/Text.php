<?php
declare(strict_types=1);

namespace Besnik\LaravelFiltering\Filtering\Fields\Text;

use Besnik\LaravelFiltering\Filtering\Contracts\FieldContract;
use Besnik\LaravelFiltering\Filtering\Enums\Condition;
use Illuminate\Database\Eloquent\Collection;

class Text extends FieldContract
{
    protected string $type = 'text';
    protected mixed $conditions;

    public function conditions(callable $callable): self
    {
      call_user_func($callable);

      return $this;
    }

    protected function options(array|Collection $options)
    {
        $this->equal();
    }

    public function equal(string $title = 'Equal'): self
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
            Condition::NOT_IN->value => function () use ($value) {
                $separatedBy = $this->data[Condition::NOT_IN->value]['separated_by'] ?? ',';
                $this->query->whereNotIn($this->name, explode($separatedBy, $value));
            },
            default => function () use ($value) {
                $this->query->where($this->name, '=', $value);
            }
        };
    }
}
