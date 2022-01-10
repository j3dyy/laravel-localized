<?php

namespace J3dyy\LaravelLocalized\DB;

use Illuminate\Database\Eloquent\Model;

class Locales extends Model
{
    protected $table = 'locales';

    protected $fillable = [
        'iso_code','name','original_name','is_active'
    ];
}
