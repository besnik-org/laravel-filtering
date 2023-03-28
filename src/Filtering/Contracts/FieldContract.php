<?php

namespace Besnik\LaravelFiltering\Filtering\Contracts;

use Besnik\LaravelFiltering\Filtering\Enums\TextCondition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class FieldContract
{

    /**
     * @var mixed|\Closure|null
     */
    protected mixed $callback = null;

    /**
     * @var string|null
     */
    protected ?string $placeholder = null;

    /**
     * @var string|null
     */
    protected ?string $cssClass = null;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var array
     */
    protected array $data = [
        'conditions' => [],
        'config' => []
    ];

    /**
     * @var array
     */
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

    abstract protected function default();

    private function applyFilter(): void
    {

        $value = trim(request()->input($this->name));

        if ($value) {
            if (!$this->callback) {
                $this->setQueryWithCondition();
            }
            call_user_func($this->callback, $this->query, $value);
        }
    }

    public function __call($method, $args)
    {
        match ($method) {
            'apply' => $this->applyFilter(),
        };
    }

    abstract protected function setQueryWithCondition();

    protected function conditionField(): string
    {
        return $this->name . '_condition';
    }
}
