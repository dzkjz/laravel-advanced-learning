<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    public function student()
    {
        // 会将phone模型实例中的user_id与student模型数据的id对应
        return $this->belongsTo(
            Student::class,
            'student_id',//如果phones表中不是student_id，就在这里改
            'id'//如果students表中，不是用的id做primary index键，就在这个地方改
        );
    }


}
