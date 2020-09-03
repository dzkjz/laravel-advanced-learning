<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{

    public $table = 'flights';

    protected $primaryKey = 'flight_id';

    /**
     *In addition, Eloquent assumes that the primary key is an incrementing integer value,
     * which means that by default the primary key will automatically be cast to an int.
     * If you wish to use a non-incrementing or a non-numeric primary key
     * you must set the public $incrementing property on your model to false:
     */
    public $incrementing = false;


    /**
     * If your primary key is not an integer, you should set the protected $keyType property on your model to string:
     * @var string
     */
    protected $keyType = 'string';

    /**
     * By default, Eloquent expects created_at and updated_at columns to exist on your tables.
     * If you do not wish to have these columns automatically managed by Eloquent,
     * set the $timestamps property on your model to false:
     * @var bool
     */
    public $timestamps = false;

    /**
     * If you need to customize the format of your timestamps, set the $dateFormat property on your model.
     * This property determines how date attributes are stored in the database,
     * as well as their format when the model is serialized to an array or JSON:
     * @var string
     */
    protected $dateFormat = 'U';


    /**
     * If you need to customize the names of the columns used to store the timestamps,
     * you may set the CREATED_AT and UPDATED_AT constants in your model:
     */
    const CREATED_AT = 'creation_date';

    const UPDATED_AT = 'last_update';


    /**
     * By default, all Eloquent models will use the default database connection configured for your application.
     * If you would like to specify a different connection for the model, use the $connection property:
     * @var string
     */
    protected $connection = 'connection-name';


    /**
     * If you would like to define the default values for some of your model's attributes,
     * you may define an $attributes property on your model:
     * @var bool[]
     */
    protected $attributes = [
        'delayed' => false,
    ];


    /**
     *   Create a new Eloquent Collection instance.
     * @param array $models
     * @return CustomCollection|\Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        // Once you have defined a newCollection method,
        // you will receive an instance of your custom collection anytime
        // Eloquent returns a Collection instance of that model.
        return new CustomCollection($models);
        //  If you would like to use a custom collection for every model in your application,
        // you should override the newCollection method on a base model class that is extended by all of your models.
    }

}
