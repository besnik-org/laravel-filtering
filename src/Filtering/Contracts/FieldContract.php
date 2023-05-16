<?php
declare(strict_types=1);

namespace Besnik\LaravelFiltering\Filtering\Contracts;

use Illuminate\Database\Eloquent\Builder;

abstract class FieldContract
{
    /**
     * @var mixed|\Closure|null
     */
    protected mixed $callback = null;

    protected ?string $placeholder = null;

    protected ?string $cssClass = null;

    protected string $type;

    protected array $data = [
        'conditions' => [],
        'config' => [],
    ];

    protected array $conditions = [];

    public function __construct(
        protected Builder $query,
        protected string $name,
        protected string|null $title = null,
        protected string $operator = '=',
    ) {
        if (!$this->title) {
            $this->title = ucwords(str_replace('_', ' ', $this->name));
        }

        $this->default();
    }

    abstract protected function default();

    public function callback(callable $callback): self
    {
        $this->callback = $callback;

        return $this;
    }

    public function getValue($name): mixed
    {
        return $this->request->input($name);
    }

    public function title($title): self
    {
        $this->title = $title;

        return $this;
    }

    public function placeholder($text): self
    {
        $this->placeholder = $text;

        return $this;
    }

    public function class($cssClass): self
    {
        $this->cssClass = $cssClass;

        return $this;
    }

    public function __call($method, $args)
    {
        return match ($method) {
            'apply' => $this->applyFilter(),
            'fields' => $this->getFields(),
        };
    }

    private function applyFilter()
    {
        $value = trim(request()->input($this->name));

        if ($value) {
            if (!$this->callback) {
                $this->setQueryWithCondition();
            }

            call_user_func($this->callback, $this->query, $value);
        }

        return true;
    }

    abstract protected function setQueryWithCondition();

    protected function getFields(): array
    {
        return $this->data;
    }

    protected function conditionField(): string
    {
        return $this->name.'_condition';
    }
}
