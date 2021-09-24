<?php

namespace J3dyy\LaravelLocalized\DB\Traits;

trait ResolveTranslatable
{

    function resolve(){
        $tableName = $this->getTable();
        $this->resolveKeys($tableName);
        $related =  str_replace(config('localized.translated_endpoint'),"",get_class($this));
        $this->related = $related;
    }

    private function resolveKeys(string $name): void{
        $ent = '_'.strtolower(config('localized.translated_endpoint'));
        $str = explode($ent,$name);
        $this->foreignKey = $str[0].'_id';
    }

}
