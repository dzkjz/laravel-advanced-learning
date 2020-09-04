<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Image;
use App\Models\Phone;
use App\Models\Student;
use App\Models\Teacher;
use Facade\Ignition\Tabs\Tab;
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

    public function eagerLoading()
    {
        // 当以属性方式访问 Eloquent 关联时，关联数据「懒加载」。
        //这意味着直到第一次访问属性时关联数据才会被真实加载。

        //有这样一个查询
        //如果数据表里有25个teacher
        $teachers = Teacher::all();
        //第一个查询
        foreach ($teachers as $teacher) {
            //循环25次执行查询
            $teacher->car->brand; // 直到第一次访问属性时关联数据才会被真实加载。
        }
        //一共执行1+25次查询 即N+1【N个结果+1】


        //但是如果我们使用预加载
        $teachers = Teacher::with('car')->get();
        //第一次查询teachers
        //select * from teachers
        //第二次据[teachers ids]查询relations 【只有 2 个查询】
        //select * from cars where id in (1, 2, 3, 4, 5, ...)

        //下面无查询，只有迭代输出结果

        foreach ($teachers as $teacher) {
            echo $teacher->car->brand;
        }

        //预加载多个关联
        $teachers = Teacher::with(['car', 'students'])->get();

        //嵌套预加载
        $teachers = Teacher::with('students.phones')->get();

        // 嵌套预加载 morphTo 关联 太复杂 不过 参照该格式会用即可


        // 预加载指定列
        //并不是总需要获取关系的每一列。在这种情况下，Eloquent 允许你为关联指定想要获取的列：
        $teachers = Teacher::with('students:id,name')  //注意：在使用这个特性时，一定要在要获取的列的列表中包含 id 列。
        ->get();


        //为预加载添加约束 【仅仅预加载那些name里包含Jimmy%的students关联数据】
        $teachers = Teacher::with(['students' => function (Builder $query) {
            $query->where('name', 'like', 'Jimmy%');
        }])->get();

        $teachers = Teacher::with(['students' => function (Builder $query) {
            // 调用其它的 查询构造器 方法进一步自定义预加载操作：
            $query->orderBy('created_at', 'desc');
            // 注意：在约束预加载时，不能使用 limit 和 take 查询构造器方法。
        }])->get();

        //延迟预加载

        $teachers = Teacher::all();
        if ($teacher) {
            $teachers->load('students');
        }

        // 如果你想要在渴求式加载的查询语句中进行条件约束，你可以通过数组的形式去加载，键为对应的关联关系，值为 Closure 闭包函数，
        //该闭包的参数为一个查询实例：

        if ($teacher) {
            $teachers->load(['students' => function (Builder $query) {
                $query->orderBy('created_at', 'asc');
            }]);
        }
        //如果希望加载还没有加载的关联关系时，你可以使用 loadMissing 方法：
        if ($teachers) {
            $teachers->loadMissing('students');
        }

        // 嵌套延迟预加载 & morphTo 见官方文档


    }

    public function updateInsertTest()
    {
        /** 保存方法 */
        $student = Student::find(1);
        $phone = Phone::find(100);
        $student->phone() // 我们并没有使用动态属性的方式访问 phone 关联。相反，我们调用 phone 方法来获得关联实例。
        ->save($phone);// save 方法将自动添加适当的 student_id 值到 Phone 模型中。

        // 如果你需要保存多个关联模型，你可以使用 saveMany 方法：
        $student->phone()->saveMany([
            $phone,
            new Phone(['brand' => 'Samsung']),
        ]);


        // 递归保存模型和关联数据
        //如果你想 save 你的模型及其所有关联数据，你可以使用 push 方法:
        $student->teachers[0]->name = 'Jimmy Horton';
        $student->teachers[0]->car->brand = 'Valkswagon';
        $student->push();


        /** 新增方法 */

        $phone = $student->phone()
            ->create(// create 方法。它接受一个属性数组，同时会创建模型并插入到数据库中
                [
                    'brand' => 'HTC',
                ]
            );
        // save 方法和 create 方法的不同之处在于， save 方法接受一个完整的 Eloquent 模型实例，而 create 则接受普通的 PHP 数组:

        // 你还可以使用 createMany 方法去创建多个关联模型：
        $student->phone()
            ->createMany(
                [
                    'brand' => 'Xiaomi',
                ],
                [
                    'brand' => 'Oppo',
                ]
            );

        // 你还可以使用 findOrNew、firstOrNew、firstOrCreate 和 updateOrCreate 方法来 创建和更新关系模型。


        /** 更新 belongsTo 关联 */
        $grade = Grade::find(1);
        $student->teachers()->associate($grade);
        $student->save();

        // 当移除 belongsTo 关联时，可以使用 dissociate 方法。此方法会将关联外键设置为 null:
        $student->dissociate($grade);
        $student->save();

        // 默认模型 【这个时定义在model类中的】
        // belongsTo，hasOne，hasOneThrough 和 morphOne 关系允许你指定默认模型，当给定关系为 null 时，将会返回默认模型。
        // 这种模式被称作 空对象模式 ，可以减少你代码中不必要的检查。
        //在下面的例子中，如果学生没有找到班级， grade 关系会返回一个空的 App\Models\Grade 模型：
        ///**
        // * Get the grade of the student.
        // */
        //public function grade()
        //{
        //    return $this->belongsTo('App\Models\Grade')->withDefault();
        //}

        // 如果需要在默认模型里添加属性， 你可以传递数组或者回调方法到 withDefault 中：

        //  return $this->belongsTo('App\Models\Grade')->withDefault([
        //        'name' => 'new Class no name',
        //    ]);

        // 以及闭包式
//       return $this->belongsTo('App\Models\Grade')->withDefault(function ($grade, $student) {
//            $grade->name = new Class no name'.$student->name;
//        });


        /** 多对多关联 */

        /**
         * 附加 / 分离
         */
        // 给某个用户附加一个角色是通过向中间表插入一条记录实现的，可以使用 attach 方法完成该操作：
        $teacher = Teacher::find(1);
        $student->teachers()->attach($teacher->id);

        // 在将关系附加到模型时，还可以传递一组要插入到中间表中的附加数据：
        $student->teachers()->attach($teacher->id, ['updated_at' => now()->addMinutes(1)]);

        // 当然，有时也需要移除用户的角色。可以使用 detach 移除多对多关联记录。
        //detach 方法将会移除中间表对应的记录；但是这两个模型都将会保留在数据库中：

        $student->teachers()->detach($teacher->id);

        //移除所有
        $student->teachers()->detach();

        // 为了方便，attach 和 detach 也允许传递一个 ID 数组：
        $student->teachers()->detach([1, 2, 3]);
        $student->teachers()->attach([1 => ['updated_at' => now()->addMinutes(1)], 2, 3]);

        // 你也可以使用 sync 方法构建多对多关联。
        // sync 方法接收一个 ID 数组以替换中间表的记录。
        // 中间表记录中，所有未在 ID 数组中的记录都将会被移除。
        // 所以该操作结束后，只有给出数组的 ID 会被保留在中间表中：

        $student->teachers()->sync([1, 3, 4, 5]);
        //你也可以通过 ID 传递额外的附加数据到中间表：
        $student->teachers()->sync([1 => ['updated_at' => now()->addMinutes(1)], 3, 4, 5]);

        //如果你不想移除现有的 ID，可以使用 syncWithoutDetaching 方法：
        $student->teachers()->syncWithoutDetaching([1, 2, 3]);

        // 切换关联
        // 如果给定的 ID 已被附加在中间表中，那么它将会被移除，
        // 同样，如果给定的 ID 已被移除，它将会被附加：
        $student->teachers()->toggle([1, 2, 5, 6]);


        // 在中间表上保存额外的数据
        Student::find(1)->roles()->save($teacher, ['expires' => now()->addMinutes(1440)]);


        // 更新中间表记录

        // 如果你需要在中间表中更新一条已存在的记录，可以使用 updateExistingPivot 。
        // 此方法接收中间表的外键与要更新的数据数组进行更新：
        $attributes = ['created_at' => now()->addMinutes(1)];
        $student->teachers()->updateExistingPivot($teacher->id, $attributes);


        /** 更新父级时间戳 */


        // 当一个模型属 belongsTo 或者 belongsToMany 另一个模型时，
        // 例如 Student 属于 Grade，有时更新子模型导致更新父模型时间戳非常有用。
        // 例如，当 Student 模型被更新时，您要自动「触发」父级 Grade 模型的 updated_at 时间戳的更新。
        // Eloquent 让它变得简单。
        // 只要在子模型加一个包含关联名称的 touches 属性即可：


        // 已在 Student 模型中添加好：     protected $touches = ['grade'];

        // 现在，当你更新一个 Student 时，对应父级 Grade 模型的 updated_at 字段同时也会被更新，
        // 使其更方便得知何时让一个 Grade 模型的缓存失效：
        $student->name = 'John jr People';
        $student->save();


    }
}
