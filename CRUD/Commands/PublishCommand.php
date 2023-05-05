<?php

namespace Besnik\LaravelInertiaCrud\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inertia-crud:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all of the Inertia CRUD resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

//        $this->comment('Publishing Horizon Service Provider...');
//        $this->callSilent('vendor:publish', ['--tag' => 'horizon-provider']);

        $this->comment('Publishing Inertia Crud Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'inertia-crud-assets']);

//        $this->comment('Publishing Horizon Configuration...');
//        $this->callSilent('vendor:publish', ['--tag' => 'horizon-config']);

        $this->info('Inertia Crud scaffolding installed successfully.');
    }
}