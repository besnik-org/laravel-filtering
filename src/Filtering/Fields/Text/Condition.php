<?php

namespace Besnik\LaravelFiltering\Filtering\Fields\Text;

use Besnik\LaravelFiltering\Filtering\Contracts\ConditionContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Condition
{
    public ?string $prefix = null;
    public ?string $suffix = null;
    public ?string $in = null;
    public ?string $equal = null;
    public ?string $notEqual = null;
    public ?string $inExplode = ',';
    public array $data = [];

    public function equal(string $title = null):self
    {
        $this->equal = $title ?? 'Equal';

        return $this;
    }

    public function notEqual(string $title = null):self
    {
        $this->notEqual = $title ?? 'Not Equal';
        return $this;
    }

    public function prefix(string $title = null):self
    {
        $this->prefix = $title ?? 'Start With';
        return $this;
    }

    public function suffix(string $title = null):self
    {
        $this->suffix = $title ?? 'End With';
        return $this;
    }

    /**
     * if value is not array then we will explode value with this symbol
     * @param string $separatedBy
     * @param string|null $title
     * @return $this
     */
    public function in( string $separatedBy = ',', ?string $title = null ):self
    {
        $this->inExplode =  $separatedBy;
        $this->in = $title ?? 'End With';
        return $this;
    }

    /**
     * if value is not array then we will explode value with this symbol
     * @return $this
     */
    public function and(string $title = null):Condition
    {
       return $this->field->condition($title);
    }
}
