<?php

namespace J3dyy\LaravelLocalized\DB;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;

class LocalizedModel extends Model
{


    /**
     * @throws BindingResolutionException
     */
    protected function makeModel(Model $root, $endpoint = ''){
        return app()->make(get_class($root).$endpoint);
    }

}
