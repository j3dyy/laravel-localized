<?php

namespace J3dyy\LaravelLocalized\DB;

use Illuminate\Database\Eloquent\Model;
use J3dyy\LaravelLocalized\DB\Traits\ResolveTranslatable;
use J3dyy\LaravelLocalized\exceptions\LocalizedImplementationException;

class Translatable extends LocalizedModel
{

    use ResolveTranslatable;

    protected $foreignKey;

    protected $ownerKey = 'id';

    private $related;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->resolve();
    }

    public function standFor(){
        return $this->belongsTo($this->related, $this->foreignKey , $this->ownerKey);
    }

    public function getForeignKey()
    {
        return $this->foreignKey;
    }


}
