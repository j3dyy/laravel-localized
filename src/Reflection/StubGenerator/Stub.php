<?php

namespace J3dyy\LaravelLocalized\Reflection\StubGenerator;

use J3dyy\LaravelLocalized\IO\Writer\StubWriter;
use J3dyy\LaravelLocalized\Reflection\StubGenerator\Generators\Generator;

/**
 * @author j3dy
 */
class Stub
{

    /**
     * @param Generator $generator
     * @param $path
     */
    public static function load(Generator $generator,$path)
    {
        $generator->write(new StubWriter($path));
    }

}
