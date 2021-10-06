<?php

namespace J3dyy\LaravelLocalized\Console;

use Illuminate\Console\Command;
use J3dyy\LaravelLocalized\Reflection\StubGenerator\Generators\Generator;
use J3dyy\LaravelLocalized\Reflection\StubGenerator\Generators\MigrationGenerator;
use J3dyy\LaravelLocalized\Reflection\StubGenerator\Generators\ModelGenerator;
use J3dyy\LaravelLocalized\Reflection\StubGenerator\Stub;

/**
 * @author j3dy
 */
class MakeModelCommand extends Command
{
    protected $translationEndpoint = null;

    protected $signature = 'make:localized_model {model} {--model=} {--migration=}';

    protected $description = 'make Entity and Translated models';

    public function __construct()
    {
        parent::__construct();
        $this->translationEndpoint = config('localized.translated_endpoint');
    }

    public function handle()
    {
        $model = $this->argument('model');

        $path = $this->option('model');
        $migrationPath = $this->option('migration');

        $this->info('Generating STUB Mode');
        //generate model
        $this->handleLoad(new ModelGenerator($model),$path == null ? app_path('Models/') : base_path($path));

        //generate migration
        $this->handleLoad(new MigrationGenerator($model),$migrationPath == null ? database_path('migrations/') : base_path($migrationPath));
        $this->info('Well Done');
    }

    private function handleLoad(Generator $generator, string $migrationPath){
        Stub::load($generator,$migrationPath);
    }

}
