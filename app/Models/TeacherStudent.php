<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

// Pivot 模型可能不使用 SoftDeletes 特性。 如果您需要软删除数据关联记录，请考虑将您的数据关联模型转换为实际的 Eloquent 模型。
class TeacherStudent extends Pivot
{
    //如果你用一个自定义的中继模型TeacherStudent 定义了多对多[teacher 及 student 之间]的关系，
    //而且这个中继模型拥有一个自增的主键，
    //你应当确保这个自定义中继模型类TeacherStudent中定义了一个 incrementing 属性其值为 true 。
    public $incrementing = true;



}
