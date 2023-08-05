<?php

declare(strict_types=1);

namespace Besnik\LaravelFiltering;

use App\Models\User;
use Besnik\LaravelFiltering\Filter\Conditions\TextCondition;
use Besnik\LaravelFiltering\Filter\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as DBBuilder;

class UserFilter extends Filter
{
    protected function model(): Builder|DBBuilder|Model|string
    {
      return User::class;
    }

    protected function fields(): array
    {
        return [
            $this->text('name')->conditions(),
            $this->text('email')->conditions(),
        ];
    }
}
