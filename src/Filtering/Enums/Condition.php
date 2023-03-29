<?php declare(strict_types=1);

namespace Besnik\LaravelFiltering\Filtering\Enums;

enum Condition: string
{
    case EQUAL = 'equal';
    case NOT_EQUAL = 'not_equal';
    case IN = 'in';
    case NOT_IN = 'not_in';
    case START_WITH = 'start_with';
    case END_WITH = 'end_with';
    case CONTAIN = 'contain';
}
