<?php
declare(strict_types=1);

namespace Besnik\LaravelFiltering\Exceptions;

use Exception;

class FilterException extends Exception
{
    public static function NoFilterFound($filterName): self
    {
        return new static("Sorry, the filter '{$filterName}' does not exist.");
    }
}
