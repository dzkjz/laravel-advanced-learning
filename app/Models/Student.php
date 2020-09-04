<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    /**
     * 当一个模型属 belongsTo 或者 belongsToMany 另一个模型时，
     * 例如 Student 属于 Grade，有时更新子模型导致更新父模型时间戳非常有用。
     * 例如，当 Student 模型被更新时，您要自动「触发」父级 Grade 模型的 updated_at 时间戳的更新。
     * Eloquent 让它变得简单。
     * 只要在子模型加一个包含关联名称的 touches 属性即可：
     * @var string[]
     */
    protected $touches = ['grade'];

    public function phone()
    {
        return $this->hasOne(Phone::class, 'student_id', 'id');
        //这么理解，由于是hasOne关系，所以应该是被拥有的model里面存着主人的primary键，就是phones表存着student的键
        //所以外键就是phones表中存着的student_id，而对应student_id来找student模型，就是students表中的id和phones表中的student_id对比
        // 换句话说，Eloquent 将会在 Phone 记录的 student_id 列中查找与students表的 id 列相匹配的值。

        //对应的在Phone模型类中 添加的是 belongsTo方法，但是外键和对应students表的键设置与上相同；

    }

    public function grade()
    {
        return $this->belongsTo(
            Grade::class,
            'grade_id',
            'id'
        );
    }

    public function teachers()
    {
        return $this->belongsToMany(
            Teacher::class,
            'teacher_student',//关联连接表 pivot表 表名
            'student_id',//此关联的模型在连接表里的外键名
            'teacher_id'// 另一个模型在连接表里的外键名
        )
            //默认情况下，pivot对象只包含两个关联模型的主键，如果你的中间表里还有其他额外字段，请在定义关联时显示明确的指出来:
//            ->withPivot('column1','column2')
            ->withTimestamps()//如果需要中间表自动维护created_at和updated_at时间戳，那么在定义关联时附加上withTimestamps方法即可
            ;


    }

    /** 获取student的图片
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');//对应到images表中 imageable_id及imageable_type
    }

    /** 获取这个学生点的所有菜的菜名
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function dishes()
    {
        return $this->morphToMany(Dish::class, 'dishable');
    }
}
