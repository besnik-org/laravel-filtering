<?php

namespace Besnik\LaravelFiltering;

use Illuminate\Http\Request;

abstract class FilteringContract
{
    public abstract function process(Request $request, Filter $filter): Filter;
}