## Laravel Localized

## Installation
```composer
composer require j3dyy/laravel-localized
```

```
php artisan vendor:publish --tag=laravel-localized
```

then we need to migrate 
```
php artisan migrate
```
this creates Table locales where you  store your localizations ex : en, ka, gb, ru ...

## Generating Migration

```php
php artisan make:localized_model YourModelName
```
This will generate 2 of files your base model named "YourModelName" and translatable model
"YourModelNameTranslation" prefix name based on configuration file localized.php
 2 migration files : X_create_yourmodelname_table ,
X_create_yourmodelname_translations_table

```php
// config config/localized.php
'translated_endpoint' => 'Translation',
```

### The Models
Localized : Contains only not translate field
Translatable : Holds Translated values 

#### Save entity
```php
// example data
$data = [
    'is_active'=>true,
    'en'    => [
        'name'  => 'n',
        'description' => 'd'
    ],
    'ka'    => [
        'name'  => 'sdsdsds',
        'description' => 'sdsds'
    ]
];
//storing this data creates 2 translation for model , for en and for ka seperated with locale key
```


Structured model example 

```php
Team extends Localized{
    protected $fields = [ 'is_active', 'sort_order' , /* ...etc fields */ ]
}

TeamTranslation extends Translatable{
  protected $fields = [ 'locale', 'name', 'description', /* ...etc fields */ ]
}
```

```php
//get translated team
$team = Team::find(1);

// return translated model or null
// if translation parameter null translated model will fetched from App::getLocale() < locale
$team->translated('en');

//also we can get translated fields like
$team->name;
// same as 
$team->translated('default_fallback_locale')->name
```
