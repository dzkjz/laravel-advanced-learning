<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;

/**
 * 【inbound cast：】
 *
 * Occasionally, you may need to write a custom cast that only transforms values that are being
 * set on the model and does not perform any operations when attributes are being retrieved from the model.
 * A classic example of an inbound only cast is a "hashing" cast.
 * Inbound only custom casts should implement the CastsInboundAttributes interface,
 * which only requires a set method to be defined.
 * 只对内设置的时候cast，取出值的时候，不cast
 * Class Hash
 * @package App\Casts
 */
class Hash implements CastsInboundAttributes
{

    /**
     * @var null
     */
    protected $algorithm;

    public function __construct($algorithm = null)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param array $value
     * @param array $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
        return is_null($this->algorithm)
            ? bcrypt($value)
            : hash($this->algorithm, $value);
    }
}
