<?php

namespace Besnik\LaravelFiltering\Exceptions;

class FilterException extends \Exception
{
    public static function NoFilterFound($filterName): self
    {
        return new static("Sorry, the filter '{$filterName}' does not exist.");
    }
}

