<?php

namespace Besnik\LaravelInertiaCrud\Utilities;

class MessageBucket
{
    protected static array $info = [];
    protected static array $errors = [];


    public static function addInfo($message): void
    {
        static::$info[] = $message;
    }

    public static function addError($message): void
    {
        static::$errors[] = $message;
    }

}