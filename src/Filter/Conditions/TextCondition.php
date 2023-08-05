<?php

namespace Besnik\LaravelFiltering\Filter\Conditions;

use Besnik\LaravelFiltering\Filtering\Enums\Condition as ConditionEnum;

class TextCondition
{

    public static function equal(string $title = 'Equal'): array
    {
        return [
            ConditionEnum::EQUAL->value => ['title' => $title]
        ];
    }

    public static function notEqual(string $title = 'Not Equal'): array
    {
        return [
            ConditionEnum::NOT_EQUAL->value => ['title' => $title]
        ];
    }

    public static function contain(string $title = 'Contain'): array
    {
        return [
            ConditionEnum::CONTAIN->value => ['title' => $title]
        ];
    }

    public static function startWith(string $title = 'Start With'): array
    {
        return [
            ConditionEnum::START_WITH->value => ['title' => $title]
        ];
    }

    public static function endWith(string $title = 'End With'): array
    {
        return [
            ConditionEnum::END_WITH->value => ['title' => $title]
        ];
    }

    public static function in(?string $title = 'In', string $separatedBy = ','): array
    {
        return [
            ConditionEnum::IN->value => [
                'title' => $title,
                'separated_by' => $separatedBy,
            ]
        ];
    }

    public static function notIn(string $separatedBy = ',', ?string $title = 'In'): array
    {
        return [
            ConditionEnum::NOT_IN->value => [
                'title' => $title,
                'separated_by' => $separatedBy,
            ]
        ];
    }

    public static function all(): array
    {
        return [
            ConditionEnum::EQUAL->value => ['title' => 'Equal'],
            ConditionEnum::NOT_EQUAL->value => ['title' => 'Not Equal'],
            ConditionEnum::CONTAIN->value => ['title' => 'Contain'],
            ConditionEnum::START_WITH->value => ['title' => 'Start With'],
            ConditionEnum::END_WITH->value => ['title' => 'End With'],
            ConditionEnum::IN->value => ['title' => 'In', 'separated_by' => ','],
            ConditionEnum::NOT_IN->value => ['title' => 'Not In', 'separated_by' => ','],
        ];
    }
}
