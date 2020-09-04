<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    public function students()
    {
        // By convention, Eloquent will take the "snake case" name of the owning model and suffix it with _id.
        // So, for this example, Eloquent will assume the foreign key on the Student model is grade_id.
        return $this->hasMany(Student::class);
    }

    public function phones()
    {
        // 虽然phones表中不包含任何grade_id，但是hasManyThrough方法依然可以实现$grade->phones的访问
        // Eloquent会先检查中间表students表的grade_id字段，找到所有匹配的student的id之后，
        // 再依据对应的student_id【phones表中】完成对phone的查找
        return $this->hasManyThrough(
            Phone::class,
            Student::class,
            //如果有下面的自定义的键，做个参考:
            'grade_id',//【中间模型的外键名】students表中的外键【Eloquent会先检查中间表students表的grade_id字段】
            'student_id',//【最终模型的外键名】phones表中的外键【再依据对应的student_id【phones表中】完成对phone的查找】
            'id',//中间表students表的grade_id字段对应到grade模型的主键
            'id'//phones表中外键student_id对应到student模型的主键
        );
    }

}
