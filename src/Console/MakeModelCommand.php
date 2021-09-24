<?php

namespace J3dyy\LaravelLocalized\Console;

use Illuminate\Console\Command;
use J3dyy\LaravelLocalized\Reflection\StubGenerator\Generators\MigrationGenerator;
use J3dyy\LaravelLocalized\Reflection\StubGenerator\Generators\ModelGenerator;
use J3dyy\LaravelLocalized\Reflection\StubGenerator\Stub;

/**
 * @author j3dy
 */
class MakeModelCommand extends Command
{
    protected $translationEndpoint = null;

    protected $signature = 'make:localized_model {model}';

    protected $description = 'make Entity and Translated models';

    public function __construct()
    {
        parent::__construct();
        $this->translationEndpoint = config('localized.translated_endpoint');
    }

    public function handle()
    {
        $model = $this->argument('model');

        //generate model
        $this->info('Generating STUB Mode');
        Stub::load(new ModelGenerator($model),app_path('Models/'));

        //generate migration
        Stub::load(new MigrationGenerator($model),database_path('migrations/'));
        $this->info('Well Done');

    }

}
