<?php

namespace Besnik\LaravelInertiaCrud\Enums;

class FieldType
{
    private static  array $types = [
        'text' =>  'string',
        'rich_text' =>  'text',
        'rich_text_log' =>  'longText',
        'number' =>  'integer',
        'number_large' =>  'bigInteger',
        'decimal_number' =>  'decimal',
        'file' =>  'string',
        'checkbox' =>  'bool',
        'select_enum' =>  'string',
        'select' =>  'foreign',
    ];

    private static  array $typesForPhp = [
        'text' =>  'string',
        'rich_text' =>  'string',
        'rich_text_log' =>  'string',
        'number' =>  'int',
        'number_large' =>  'int',
        'decimal_number' =>  'float',
        'file' =>  'string',
        'checkbox' =>  'bool',
        'select_enum' =>  'string',
        'select' =>  'int|string',
    ];



    public static function all(): array
    {
       return static::$types;
    }

    public static function from($key): mixed
    {
       return static::$types[$key];
    }

    public static function fromPhp($key): mixed
    {
       return static::$typesForPhp[$key];
    }
}