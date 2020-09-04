<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    /**
     * 获取拥有此图片的模型
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}
