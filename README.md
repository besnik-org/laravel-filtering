# laravel-filtering

```shell
php artisan vendor:publish --provider="Besnik\LaravelFiltering\LaravelFilteringServiceProvider" --tag="besnik-filtering-config"
```

#### Option field Config:

1. One

```injectablephp
 [
    'options' => [],
    'label_key' => 'name',
    'value_key' => 'id',
 ]
```

2. Two

```injectablephp
 [
    'model' => User::class,
    'label_key' => 'name',
    'value_key' => 'id',
 ]
```

3. Three

```injectablephp
 [
    'model' => User::class,
    'label_key' => 'name',
    'value_key' => 'id',
    'search' => true
 ]
```

3. Three

```injectablephp
 [
    'api' => 'url',
    'method' => 'get',
    'label_key' => 'name',
    'value_key' => 'id',
    'search' => true //optional 
 ]
```