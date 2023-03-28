<?php

namespace Besnik\LaravelFiltering\controllers;

use App\Http\Controllers\Controller;
use Besnik\LaravelFiltering\Exceptions\FilterException;
use Besnik\LaravelFiltering\Filter;
use Besnik\LaravelFiltering\FilteringContract;
use Illuminate\Http\Request;

class FilterController extends Controller
{

    /**
     * @throws FilterException
     */
    public function index(string $filterName, Request $request)
    {
        /** @var FilteringContract $filterClass */
        $filterClass = config('laravel-filtering.'.$filterName);

        if (!is_subclass_of($filterClass, FilteringContract::class)) {
            throw FilterException::NoFilterFound($filterName);
        }

        $process = (new $filterClass())->process($request, new Filter());

        return $process->results();
    }

}