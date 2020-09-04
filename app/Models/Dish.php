<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    /** 获取点了这个菜的学生
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function students()
    {
        return $this->morphedByMany(Student::class, 'dishable');
    }

    /** 获取点了这个菜的老师
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function teachers()
    {
        return $this->morphedByMany(Teacher::class, 'dishable');
    }
}
