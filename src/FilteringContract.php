<?php
declare(strict_types=1);

namespace Besnik\LaravelFiltering;

use Illuminate\Http\Request;

abstract class FilteringContract
{
    abstract public function process(Request $request, Filter $filter): Filter;
}
