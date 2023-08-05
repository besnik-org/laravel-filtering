<?php
declare(strict_types=1);

namespace Besnik\LaravelFiltering\Filtering;

use \Besnik\LaravelFiltering\Filtering\Enums\Condition as ConditionEnum;

class Condition
{
    protected static array $data;

    public static function equal(string $title = 'Equal'): void
    {
        self::$data[ConditionEnum::EQUAL->value] = ['title' => $title];
    }

    public static function notEqual(string $title = 'Not Equal'): void
    {
        self::$data[ConditionEnum::NOT_EQUAL->value] = ['title' => $title];
    }

    public static function contain(string $title = 'Contain'): void
    {
        self::$data[ConditionEnum::CONTAIN->value] = ['title' => $title];
    }

    public static function startWith(string $title = 'Start With'): void
    {
        self::$data[ConditionEnum::START_WITH->value] = ['title' => $title];
    }

    public static function endWith(string $title = 'End With'): void
    {
        self::$data[ConditionEnum::END_WITH->value] = ['title' => $title];
    }

    public static function in(?string $title = 'In', string $separatedBy = ','): void
    {
        self::$data[ConditionEnum::IN->value] = [
            'title' => $title,
            'separated_by' => $separatedBy,
        ];
    }

    public static function notIn(string $separatedBy = ',', ?string $title = 'In'): void
    {
        self::$data[ConditionEnum::NOT_IN->value] = [
            'title' => $title,
            'separated_by' => $separatedBy,
        ];
    }

    public static function all(): void
    {
        self::equal();
        self::notEqual();
        self::contain();
        self::in();
        self::notIn();
        self::startWith();
        self::endWith();
    }
}
