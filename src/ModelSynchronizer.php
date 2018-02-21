<?php
namespace Plokko\LaravelFirebase;


use Illuminate\Database\Eloquent\Model;
use Plokko\Firebase\IO\Reference;
use Plokko\LaravelFirebase\Facades\FirebaseDb;

class ModelSynchronizer
{
    private function __construct(){}

    private static function getData(Model $model){
        return $model->getFirebaseSyncData();
    }

    /**
     * @param Model $model
     * @return Reference
     */
    private static function getReference(Model $model){
        return FirebaseDb::getReference($model->getFirebaseReferenceName().'/'.$model->getKey());
    }

    /**
     * @param Model $model model to be created
     */
    static function create(Model $model){
        self::getReference($model)->set(self::getData($model));
    }

    /**
     * @param Model $model model to be deleted
     */
    static function delete(Model $model){
        self::getReference($model)->delete();
    }

    /**
     * @param Model $model
     * @param array|null $updatedFields if present only the keys in array will be updated
     */
    static function update(Model $model,array $updatedFields=null){
        $data=self::getData($model);
        if($updatedFields){
            $data = array_intersect_key($data, array_flip($updatedFields));
            self::getReference($model)->update('',$data);
        }else{
            self::getReference($model)->set($data);
        }
    }


}