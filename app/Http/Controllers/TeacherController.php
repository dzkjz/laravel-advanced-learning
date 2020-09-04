<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function getPivotRelations()
    {


        $teachers = Teacher::with('students')->get();

        foreach ($teachers->flatMap->students as $student) {
            //因在Teacher模型中定义了as('learn')中间表名称
            echo $student->learn->created_at;
        }
    }

    public function getImagesOfTeacher()
    {
        $teachers = Teacher::all();

        $images = $teachers->flatMap->images;

        $teacher = Teacher::query()->find(1);

        $images = $teacher->images;

    }

    public function getImageableInstanceThroughImage()
    {
        $image = Image::query()->first();
        $imageable = $image->imageable;//返回的将是Teacher或Student实例,结果取决于图片属于哪个模型
    }
}
