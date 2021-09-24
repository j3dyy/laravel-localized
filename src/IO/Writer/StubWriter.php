<?php

namespace J3dyy\LaravelLocalized\IO\Writer;

/**
 * @author j3dy
 */
class StubWriter implements Writable
{

    protected $path;

   public function __construct($path){
       $this->path = $path;
   }

    function write($data, string $name)
    {
        file_put_contents($this->path.$name, '<?php '.$data);
    }
}
