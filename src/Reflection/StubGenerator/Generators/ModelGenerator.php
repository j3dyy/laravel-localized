<?php

namespace J3dyy\LaravelLocalized\Reflection\StubGenerator\Generators;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use J3dyy\LaravelLocalized\DB\Localized;
use J3dyy\LaravelLocalized\DB\Translatable;
use J3dyy\LaravelLocalized\IO\Writer\Writable;
use Nette\PhpGenerator\PhpNamespace;

/**
 * @author j3dy
 */
class ModelGenerator extends Generator
{

    protected $childEntity;

    public function __construct(string $modelName)
    {
        parent::__construct($modelName);

        $this->class
            ->addUse(HasFactory::class)
            ->addUse(Localized::class)
            ->addClass($this->modelName)
            ->addExtend(Localized::class)
            ->addTrait(HasFactory::class);

        $this->childEntity = new PhpNamespace($this->nameSpace);
        $this->childEntity
            ->addUse(HasFactory::class)
            ->addUse(Translatable::class)
            ->addClass($this->modelName.config('localized.translated_endpoint'))
            ->addExtend(Translatable::class)
            ->addTrait(HasFactory::class);
    }


    public function write(Writable $writable): void
    {
        $writable->write($this->class, $this->modelName.'.php');
        $writable->write($this->childEntity, $this->modelName.config('localized.translated_endpoint').'.php');
    }

}
