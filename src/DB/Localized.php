<?php
namespace J3dyy\LaravelLocalized\DB;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;
use J3dyy\LaravelLocalized\DB\Traits\ResolveLocalized;
use J3dyy\LaravelLocalized\exceptions\LocalizedImplementationException;

class Localized extends LocalizedModel
{
    use ResolveLocalized;

    protected $translationEndpoint = null;

    protected $table;

    protected $translation = null;

    protected $with = [
        'translated'
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->translationEndpoint = config('localized.translated_endpoint');

        $this->resolve();
    }


    public function __get($key)
    {
        //first laravel magic
        $parent = parent::__get($key);

        //then localized :)
        if ($parent == null)
        {
            $this->translation ?? $this->translation = $this->translated()->first();
            return $this->translation->{$key};
        }
        return $parent;
    }



    /**
     * @throws LocalizedImplementationException
     * @throws BindingResolutionException
     */
    public function translations(): HasMany {
        $model = $this->makeModel($this,$this->translationEndpoint);
        if (!$model instanceof Translatable) throw new LocalizedImplementationException("Translation Model must be extend from translatable");

        return $this->hasMany(get_class($model));
    }


    /**
     * @throws LocalizedImplementationException|BindingResolutionException
     */
    public function translated(string $locale = null): HasMany {
        if ($locale == null) $locale = App::getLocale();

        return $this->translations()->where('locale','=',$locale);
    }



    protected static function booted(){
        static::saved(function (Model $entity){
            $entity->syncTranslations();
        });
    }


    //todo not nice
    protected function syncTranslations(){
        $needsUpdate = [];

        foreach ($this->translations as $locale => $value ){
            $record = $this->translations()->where('locale','=', $locale)->first();

            $value['locale'] = $locale;

            if ($record != null){
                $value['id'] = $record->id;
            }

            if (!isset($value['posts_id'])) $value['posts_id'] = $this->id;

            if (is_array($value)) $needsUpdate[] = $value;
        }
        if (count($needsUpdate) > 0){
            $this->upsertData($needsUpdate,array_keys($needsUpdate[0]));
        }
    }

    private function upsertData(array $data ,$keys, $uniqueBy = 'id'){
        $this->translations()->upsert($data,$uniqueBy,$keys);
    }

}

