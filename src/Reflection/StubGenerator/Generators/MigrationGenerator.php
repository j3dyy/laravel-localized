<?php

namespace J3dyy\LaravelLocalized\Reflection\StubGenerator\Generators;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use J3dyy\LaravelLocalized\DB\Translatable;
use J3dyy\LaravelLocalized\IO\Writer\Writable;
use Nette\PhpGenerator\PhpNamespace;

/**
 * @author jedy
 */
class MigrationGenerator extends Generator
{

    protected $childMigration;

    protected $tableName;

    protected $translatedTableName;

    protected $foreignField;

    public function __construct(string $modelName)
    {
        //todo feature#1 Dirty nice will be some builder for nette/generator
        parent::__construct($modelName);

        $this->tableName = strtolower($modelName);
        $this->translatedTableName = strtolower($modelName.'_translations');

        $this->foreignField = $this->tableName.'_id';

        $this->class = new PhpNamespace('');

        $className = 'Create'.$this->modelName.'Table';
        $this->class
            ->addUse(Migration::class)
            ->addUse(Blueprint::class)
            ->addUse(Schema::class)
            ->addClass($className)
            ->addExtend(Migration::class)
            ->addMethod('up')
            ->addBody('
                Schema::create("'.$this->tableName.'",function (Blueprint $table){
                    $table->id();
                    $table->timestamps();
                });
            ');

        if (isset($this->class->getClasses()[$className])){
            $this->class->getClasses()[$className]->addMethod('down')->addbody('
                Schema::dropIfExists("'.$this->tableName.'");
            ');
        }


        $childTableName = 'Create'.$this->modelName.config('localized.translated_endpoint').'sTable';
        $this->childMigration = new PhpNamespace('');
        $this->childMigration
            ->addUse(Migration::class)
            ->addUse(Blueprint::class)
            ->addUse(Schema::class)
            ->addClass($childTableName)
            ->addExtend(Migration::class)
            ->addMethod("up")
            ->addBody('
            Schema::create("'.$this->translatedTableName.'",function (Blueprint $table){
               $table->id();
               $table->timestamps();
               $table->string(\'locale\')->index();
               $table->bigInteger(\''.$this->tableName.'_id\')->unsigned();
               $table->foreign("'.$this->foreignField.'")->references(\'id\')
               ->onDelete(\'cascade\')
               ->on("'.$this->tableName.'");
            });
        ');

        if (isset($this->childMigration->getClasses()[$childTableName])){
            $this->childMigration->getClasses()[$childTableName]->addMethod("down")
                ->addBody('
                    Schema::dropIfExists("'.$this->translatedTableName.'");
                ');
        }
    }



    public function write(Writable $writable): void
    {
        $timeNow = Carbon::now()->format('Y_m_d_hms');
        $fileName = $timeNow.'_create_'.strtolower($this->modelName).'_table'.'.php';
        $translatedFileName = $timeNow.'_create_'.strtolower($this->modelName.'_'.config('localized.translated_endpoint')).'s_table'.'.php';

        $writable->write($this->class, $fileName);
        $writable->write($this->childMigration, $translatedFileName);
    }

}
