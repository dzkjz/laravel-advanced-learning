<?php


namespace App;

use App\Casts\Json as JsonCast;
use Illuminate\Contracts\Database\Eloquent\Castable;

class Json implements Castable
{

    /**
     * @inheritDoc
     */
    public static function castUsing()
    {
        return JsonCast::class;
    }
}
