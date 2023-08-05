<?php
declare(strict_types=1);

namespace Besnik\LaravelFiltering\controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Besnik\LaravelFiltering\Exceptions\FilterException;
use Besnik\LaravelFiltering\Filter;
use Besnik\LaravelFiltering\Filter\Conditions\TextCondition;
use Besnik\LaravelFiltering\Filter\Fields\Text;
use Besnik\LaravelFiltering\Filter\TextField;
use Besnik\LaravelFiltering\Filtering\Condition;
use Besnik\LaravelFiltering\FilteringContract;
use Besnik\LaravelFiltering\UserFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    /**
     * @throws FilterException
     */
    public function index(string $filterName, Request $request)
    {
       $d = (new UserFilter())  ;

       return $d->getQuery()->paginate(10);

        $filter =  new Filter(User::class);
        $filter->text('name', '', '')
            ->conditions(function (){
                Condition::all();
            })
            ->options();

        $filter->option('name')
            ->in(['product', 'service'])
            ->equal('')
            ->callback(function ($query, $value){
                $query->where('name', $value);
            });

        dd($filter->getFields());


        /** @var FilteringContract $filterClass */
        $filterClass = config('laravel-filtering.'.$filterName);

        if (!is_subclass_of($filterClass, FilteringContract::class)) {
            throw FilterException::NoFilterFound($filterName);
        }

        $process = (new $filterClass())->process($request, new Filter());

        return $process->results();
    }
}
