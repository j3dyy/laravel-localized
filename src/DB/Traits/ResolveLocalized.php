<?php

namespace J3dyy\LaravelLocalized\DB\Traits;

/**
 * @author jedy
 */
trait ResolveLocalized
{

    function resolve(){
        $this->table = $this->resolveName();
    }


    private function resolveName(): string {
        $name = explode('\\', strtolower(get_class($this)));
        return $name[count($name) - 1];
    }

}
