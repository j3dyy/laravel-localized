<?php

namespace J3dyy\LaravelLocalized\IO\Writer;

interface Writable
{
    function write($data, string $name);
}
