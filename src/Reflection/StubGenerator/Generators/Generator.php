<?php

namespace J3dyy\LaravelLocalized\Reflection\StubGenerator\Generators;

use J3dyy\LaravelLocalized\IO\Writer\Writable;
use Nette\PhpGenerator\PhpNamespace;

/**
 * @author j3dy
 */
abstract class Generator
{
    protected $modelName;
    protected $nameSpace = 'App\Models';

    protected $class;


    public function __construct(string $modelName){
        $this->modelName = $modelName;
        $this->class = new PhpNamespace($this->nameSpace);

    }

    function getClass(): PhpNamespace{
        return $this->class;
    }

    abstract public function write(Writable $writable);
}
