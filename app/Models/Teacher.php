<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    public function students()
    {
        return $this->belongsToMany(Student::class)
            ->as('learn')//定义students与teacher关联的中间表的访问命名，默认pivot，定义之后就是learn
            ->withTimestamps()//管理 created_at 和 updated_at 时间戳
            ->using(TeacherStudent::class)
            //组合使用 using 和 withPivot 从中间表来检索列
            ->withPivot(
            //通过将列名传递给 withPivot 方法，就可以从 TeacherStudent 中间表中检索出 created_by 和 updated_by 两列数据:
                [
                    'created_by',
                    'updated_by'
                ]
            );
    }

    public function car()
    {
        return $this->hasOne(Car::class);
    }

    public function parkArea()
    {
        return $this->hasOneThrough(
            ParkArea::class, //第一个参数是[希望访问的模型]名称
            Car::class,//第二个参数是[中间模型]的名称。
            'teacher_id',//中间模型【cars表】的外键teacher_id名[teacher_id]
            'car_id',//最终模型[ParkArea]的外键名[car_id]
            'id',//teachers表的主键【中间模型cars表的外键teacher_id对应的teachers表的主键，这里是id】
            'id'//cars表的主键【最终模型对应的外键car_id的对应表cars的主键，这里是id】
        );
    }
}
