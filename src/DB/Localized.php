<?php
namespace J3dyy\LaravelLocalized\DB;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use J3dyy\LaravelLocalized\DB\Traits\ResolveLocalized;
use J3dyy\LaravelLocalized\exceptions\LocalizedImplementationException;

class Localized extends LocalizedModel
{
    use ResolveLocalized;

    protected $translationEndpoint = null;

    protected $table;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->translationEndpoint = config('localized.translated_endpoint');

        $this->resolve();
    }

    /**
     * @throws LocalizedImplementationException
     * @throws BindingResolutionException
     */
    public function translations(): HasMany{
        $model = $this->makeModel($this,$this->translationEndpoint);
        if (!$model instanceof Translatable) throw new LocalizedImplementationException("Translation Model must be extend from translatable");

        return $this->hasMany(get_class($model));
    }



    /**
     * @throws LocalizedImplementationException|BindingResolutionException
     */
    public function translated($locale){
        return $this->translations()->where('locale','=',$locale)->first();
    }



    protected static function booted(){
        static::updating(function ($entity){
            dd("onUpdate",$entity);
        });
        static::updated(function ($entity){
            dd("onUpdated",$entity);
        });
        static::saved(function (Model $entity){

            $entity->syncTranslations();
//            dd("onSaving",$entity);
        });
    }


    protected function syncTranslations(){
        $needsUpdate = [];
        $walkTo = $this->translations->filter(function($value, $key){
            return strval($key) == $value->locale;
        })->toArray();


        foreach ($walkTo as $value ){
            $record = $this->translations()->where('locale','=',$value['locale'])->first();
            if ($record != null){
                $value['id'] = $record->id;
            }
            if (!isset($value['category_id'])) $value['category_id'] = $this->id;
            $needsUpdate[] = $value;
        }

        if (count($needsUpdate) > 0){
            $keys = array_keys($needsUpdate[0]);
            $this->translations()->upsert($needsUpdate,'id',$keys);
        }
    }

}

