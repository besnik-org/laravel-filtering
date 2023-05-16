<?php

namespace Besnik\LaravelInertiaCrud\Utilities;

use Besnik\LaravelInertiaCrud\DTO\CrudDto;
use Illuminate\Support\Str;

class CrudSupports
{
    public string $tableName;
    public string $name;
    public string $route;
    public string $extraNamespace;

    public function __construct(public readonly CrudDto $crudDto)
    {
        $this->generate();
    }

    public function generate(): void
    {
        [$this->extraNamespace, $this->name] = $this->extractPathAndFileName($this->crudDto->name);

        $this->tableName = Str::plural(Str::snake($this->name));
        $this->route = $this->crudDto->route;
    }

    private function extractPathAndFileName($path): array
    {
        $directoryPath = dirname($path);
        $fileName = pathinfo($path, PATHINFO_BASENAME);

        if ($directoryPath === '.') {
            $directoryPath = '';
        }

        if ($fileName === $directoryPath) {
            $fileName = '.';
        }

        return [
            $directoryPath,
            $fileName,
        ];
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function modelSupports(): ModelSupports
    {
        app()->bindIf(ModelSupports::class, function () {
            return new ModelSupports($this);
        });

        return app()->get(ModelSupports::class);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function migrationSupports(): MigrationSupports
    {
        app()->bindIf(MigrationSupports::class, function () {
            return new MigrationSupports($this);
        });

        return app()->get(MigrationSupports::class);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function requestSupports(): RequestSupports
    {
        app()->bindIf(RequestSupports::class, function () {
            return new RequestSupports($this);
        });

        return app()->get(RequestSupports::class);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function controllerSupports(): ControllerSupports
    {
        app()->bindIf(ControllerSupports::class, function () {
            return new ControllerSupports($this);
        });

        return app()->get(ControllerSupports::class);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function dtoSupports(): DtoSupports
    {
        app()->bindIf(DtoSupports::class, function () {
            return new DtoSupports($this);
        });

        return app()->get(DtoSupports::class);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function actionSupports($type): ActionUpdateSupports
    {
        app()->bindIf(ActionUpdateSupports::class, function () use ($type) {
            return new ActionUpdateSupports($this, $type);
        });

        return app()->get(ActionUpdateSupports::class);
    }


    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function actionIndexSupports($type): ActionIndexSupports
    {
        app()->bindIf(ActionIndexSupports::class, function () use ($type) {
            return new ActionIndexSupports($this, $type);
        });

        return app()->get(ActionIndexSupports::class);
    }


    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function actionStoreSupports($type): ActionStoreSupports
    {
        app()->bindIf(ActionStoreSupports::class, function () use ($type) {
            return new ActionStoreSupports($this, $type);
        });

        return app()->get(ActionStoreSupports::class);
    }


    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function actionUpdateSupports($type): ActionUpdateSupports
    {
        app()->bindIf(ActionUpdateSupports::class, function () use ($type) {
            return new ActionUpdateSupports($this, $type);
        });

        return app()->get(ActionUpdateSupports::class);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function actionDeleteSupports($type): ActionDeleteSupports
    {
        app()->bindIf(ActionDeleteSupports::class, function () use ($type) {
            return new ActionDeleteSupports($this, $type);
        });

        return app()->get(ActionDeleteSupports::class);
    }

    public function vueSupports(): VueSupports
    {
        app()->bindIf(VueSupports::class, function () {
            return new VueSupports($this);
        });

        return app()->get(VueSupports::class);
    }


}