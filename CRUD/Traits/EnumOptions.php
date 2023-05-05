<?php

declare(strict_types=1);

namespace Besnik\LaravelInertiaCrud\Traits;

trait EnumOptions
{
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(function (self $item) {
            return [$item->value => $item->label()];
        })->all();
    }
}
