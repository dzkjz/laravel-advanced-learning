<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Image;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Builder;
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

    public function queryTest(Teacher $teacher)
    {
        // 获取至少存在一部手机的所有学生...
        $students = Student::has('phone')->get();

        // 超过三部手机的所有学生...
        $students = Student::has('phone', '>', 3)->get();

        //获取至少有一部手机的学生的所有grade
        $grades = Grade::has('students.phone')->get();

        // 如果需要更多功能，可以使用 whereHas 和 orWhereHas 方法将「where」 条件放到 has 查询上。
        // 这些方法允许你向关联加入自定义约束，比如检查评论内容：

        // 获取至少有一个学生名字带有John%关键词的班级
        $grades = Grade::whereHas('students', function (Builder $query) {
            $query->where('name', 'like', 'John%');
        })->get();

        // 获取至少有3个学生名字带有John%关键词的班级
        $grades = Grade::whereHas('students', function (Builder $query) {
            $query->where('name', 'like', 'John%');
        }, '>=', 3)->get();


        //查询不存在的关联
        $grades = Grade::doesntHave('students')->get();

        $grades = Grade::whereDoesntHave('students', function (Builder $query) {
            $query->where('name', 'like', 'John%');
        })->get();

        //用点. 可以嵌套查询
        $grades = Grade::whereDoesntHave('students.phone', function (Builder $query) {
            $query->where('brand', 'IPhone');
        })->get();

        // 多态关联查询

        // 查询 学生、老师 的name里面带有'John%'的图片
        $images = Image::whereHasMorph(
            'imageable',
            [Teacher::class, Student::class],
            function (Builder $query) {
                $query->where('name', 'like', 'John%');
            }
        )->get();

        // 查询 学生、老师 的name里面不带有'John%'的图片
        $images = Image::whereDoesntHaveMorph(
            'imageable',
            [Teacher::class, Student::class],
            function (Builder $query) {
                $query->where('name', 'like', 'John%');
            }
        )->get();

        //可以根据type添加限定 约束
        $images = Image::whereHasMorph(
            'imageable',
            [Teacher::class, Student::class],
            function (Builder $query, $type) {
                $query->where('name', 'like', 'John%');
                if ($type === 'App\Models\Student') {
                    $query->orWhere('weight', '>=', 55);
                }
            }
        )->get();


        // 您可以提供 * 作为通配符，让 Laravel 从数据库中查询所有可能的多态类型，而不是传递可能的多态模型数组。
        $images = Image::whereHasMorph(
            'imageable',
            '*',
            function (Builder $query) {
                $query->where('name', 'like', 'John%');
            }
        )->get();


        //关联模型计数
        // 如果想要只计算关联结果的统计数量而不需要真实加载它们，
        // 可以使用 withCount 方法，它将放在结果模型的 {relation}_count 列。示例如下：

        $students = Student::withCount('images')->get();

        foreach ($students as $student) {
            echo $student->images_count;//它将放在结果模型的 {relation}_count 列
        }


        //为多个关系添加 【计数】 并添加限制
        $students = Student::withCount(['phones', 'teachers' => function (Builder $query) {
            $query->where('name', 'like', 'Amy%');
        }])->get();

        echo $students[0]->phones_count;
        echo $students[0]->teachers_count;


        //给关联计数结果起一个别名，允许在同一关联上添加多个计数：
        $students = Student::withCount([
            'phones',
            'phones as brand_iphones_count' => function (Builder $query) {
                $query->where('brand', 'iphone');
            },
        ])->get();


        echo $students[0]->phones_count;
        echo $students[0]->brand_iphones_count;


        // 如果将 withCount 和 select 查询组装在一起，请确保在 select 方法之后调用 withCount ：
        $students = Student::query()->select(['name', 'weight'])->withCount('phones')->get();
        echo $students[0]->name;
        echo $students[0]->weight;
        echo $students[0]->phones_count;

        // 此外，使用 loadCount 方法，您可以在父模型被加载后使用关联计数:

        $student = Student::find(1);
        $student->loadCount('phones');

        echo $student->phones_count;


        // 如果您需要在预加载查询上设置额外的查询约束，您可以传递一个希望加载的关联数组。数组值应该是 Closure 实例，它接收查询生成器实例:
        $student->loadCount(['phones' => function (Builder $query) {
            $query->where('brand', 'iphone');
        }]);

        echo $student->phones_count;


    }
}
