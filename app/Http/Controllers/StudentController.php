<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function showPhone(Request $request)
    {
        $student = Student::find(1);
        $phone = $student->phone;
    }

    public function pivotTableTest()
    {
        $student = Student::query()->find(1);

        foreach ($student->teachers as $teacher) {
            echo $teacher->pivot//访问中间表 [获取的每一个teacher对徐都会自动被赋予pivot属性，代表中间表的一个模型对象，可以像其他的Eloquent模型一样使用]
            ->created_at;//访问中间表的属性
        }
    }

    public function getDishesOfStudent(Student $student)
    {
        $dishes = $student->dishes;

        foreach ($dishes as $dish) {
            echo $dish->name;
        }
    }
}
