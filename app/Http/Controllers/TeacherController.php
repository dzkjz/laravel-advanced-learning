<?php

namespace App\Http\Controllers;

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
}
