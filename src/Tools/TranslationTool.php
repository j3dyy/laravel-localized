<?php
namespace J3dyy\LaravelLocalized\Tools;


use Illuminate\Database\Eloquent\Model;

class TranslationTool
{

    public static function createTranslation( Model $model )
    {
        $translationEndpoint = config('localized.translated_endpoint','Translation');
        $translation = get_class($model).$translationEndpoint;
        //todo check instance
        return new $translation();
    }
}
