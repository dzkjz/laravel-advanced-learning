<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $flight = new Flight;
        $flight->name = $request->input('name');

        // The created_at and updated_at timestamps will automatically be set when the save method is called,
        // so there is no need to set them manually.
        $flight->save();


        $flight = Flight::create(['name' => 'Flight 10']);
        $flight->fill(['name' => 'Flight 22']);


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Flight $flight
     * @return \Illuminate\Http\Response
     */
    public function show(Flight $flight)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Flight $flight
     * @return \Illuminate\Http\Response
     */
    public function edit(Flight $flight)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Flight $flight
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Flight $flight)
    {
        // The save method may also be used to update models that already exist in the database.

        // To update a model, you should retrieve it,
        $flight = Flight::find(1);
        // set any attributes you wish to update,
        $flight->name = "New Flight Name";
        // and then call the save method.
        $flight->save();
        // Again, the updated_at timestamp will automatically be updated, so there is no need to manually set its value:

        /** Mass update */
        // Updates can also be performed against any number of models that match a given query.

        // In this example, all flights that are active and have a destination of San Diego will be marked as delayed:
        Flight::query()->where('active', 1)
            ->where('destination', 'San Diego')
            ->update(['delayed' => 1]); //The update method expects an array of column and value pairs representing the columns that should be updated.


        //当使用群更新的时候，模型上的saving saved updating updated事件不会因更新而触发。
        //因为群更新的时候，模型数据不会实际被retrieved。


        /** OrCreate */
        Flight::query()->firstOrCreate(['name' => 'Flight 10']);
        Flight::query()->firstOrCreate(['name' => 'Flight 10'], ['delayed' => 1, 'arrival_time' => '11:30']);

        Flight::query()->firstOrNew(['name' => 'Flight 10']);
        Flight::firstOrNew(
            ['name' => 'Flight 10'],
            ['delayed' => 1, 'arrival_time' => '11:30']
        );

        //  update an existing model or create a new model if none exists.
        $flight = Flight::updateOrCreate(
            ['departure' => 'Oakland', 'destination' => 'San Diego'],
            ['price' => 99, 'discounted' => 1]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Flight $flight
     * @return \Illuminate\Http\Response
     */
    public function destroy(Flight $flight)
    {
        // To delete a model, call the delete method on a model instance:
        $flight->delete();

        //However, if you know the primary key of the model,
        // you may delete the model without explicitly retrieving it by calling the destroy method.
        Flight::destroy(1);
        // In addition to a single primary key as its argument, the destroy method will accept multiple primary keys,
        Flight::destroy(1, 2, 3);
        // an array of primary keys,
        Flight::destroy([1, 2, 3]);
        // or a collection of primary keys:
        Flight::destroy(collect([1, 2, 3]));
        //destroy方法加载每个模型，对之使用delete方法，所以deleting和 deleted事件会被触发

        // You can also run a delete statement on a set of models.
        // In this example, we will delete all flights that are marked as inactive.
        // Like mass updates, mass deletes will not fire any model events for the models that are deleted:
        // 批量删除不会触发deleting和 deleted事件,
        // This is because the models are never actually retrieved when executing the delete statement.
        Flight::query()->where('active', 0)->delete();


        /** 如果模型支持软删除*/
        $flight->delete();
        if ($flight->trashed()) {
            //检测到被软删除了...
        }


        //因为软删除不是真删除，而是排除在查询结果之外，
        //使用withTrashed方法可以把软删除的结果一起查询出来：
        $flights = Flight::withTrashed()->where('account_id', 1)->get();

        //查询flight的history关联模型里 被软删除了的结果
        $flight->history()->withTrashed()->get();

        //只获取被软删除了的结果
        $flights = Flight::onlyTrashed()
            ->where('airline_id', 1)
            ->get();
        // 恢复被软删除了的
        $flight->restore();

        //批量查询恢复 注意不会触发restore方法会触发的事件

        Flight::withTrashed()
            ->where('airline_id', 1)
            ->restore();

        //恢复模型关联里被软删除了的结果
        $flight->history()->restore();


        //支持软删除的模型，如果需要强制删除
        $flight->forceDelete();

        //关联模型【此关联模型支持软删除】数据需要强制删除的
        $flight->history()->forceDelete();


    }

    public function subquerySelectsTest(Request $request)
    {
        // Eloquent also offers advanced subquery support,
        // which allows you to pull information from related tables in a single query.
        // For example, let's imagine that we have a table of flight destinations and a table of flights to destinations.
        // The flights table contains an arrived_at column which indicates when the flight arrived at the destination.
        //
        //Using the subquery functionality available to the select and addSelect methods,
        // we can select all of the destinations and the name of the flight
        // that most recently arrived at that destination using a single query:
        return Destination::query()->addSelect(
            [
                'last_flight' =>
                    Flight::query()->select('name')
                        ->whereColumn('destination_id', 'destinations.id')
                        ->orderBy('arrive_at', 'desc')
                        ->limit(1)
            ]
        )->get();


    }

    public function subqueryOrdering(Request $request)
    {
        return Destination::query()->orderByDesc(
            Flight::query()->select('arrive_at')
                ->whereColumn('destination_id', 'destinations.id')
                ->orderBy('arrive_at', 'desc')
                ->limit(1)
        )->get();


    }

    public function retrieveSingleModels(Request $request)
    {
        $flights = Flight::find([1, 2, 3]);

        // Sometimes you may wish to retrieve the first result of a query or
        // perform some other action if no results are found.
        // The firstOr method will return the first result that is found or,
        // if no results are found, execute the given callback.
        // The result of the callback will be considered the result of the firstOr method:
        $model = Flight::query()->where('legs', '>', 100)
            ->firstOr(function () {
                //...
            });

        $model = Flight::query()->where('legs', '>', 100)
            ->firstOr(['id', 'legs'], function () {
                //...
            });


        //如果找不到，需要抛出异常用：
        // Sometimes you may wish to throw an exception if a model is not found.
        // This is particularly useful in routes or controllers.
        // The findOrFail and firstOrFail methods will retrieve the first result of the query;
        // however, if no result is found, a Illuminate\Database\Eloquent\ModelNotFoundException will be thrown:
        $model = Flight::query()->findOrFail(1);
        $model = Flight::query()->firstOrFail();
        // If the exception is not caught, a 404 HTTP response is automatically sent back to the user.
        // It is not necessary to write explicit checks to return 404 responses when using these methods:


        //合集方法
        $count = Flight::query()->where('active', 1)->count();

        $max = Flight::query()->where('active', 1)->max('price');


    }

    public function examingAttributeChangeTest()
    {
        /** 检测模型属性改变*/

        $flight = Flight::create([
            'arrived_at' => Date::now()->addHours(2),
            'name' => 'New York 77241',

        ]);

        $flight->destination = 'San Francisco';

        // The isDirty method determines if any attributes have been changed since the model was loaded.
        $flight->isDirty();//true

        $flight->isDirty('destination');//true
        $flight->isDirty('name');//false

        $flight->isClean();//false
        $flight->isClean('destination');//false
        $flight->isClean('name');//true


        $flight->save();

        $flight->isDirty();//false
        $flight->isClean();//true

        /**修改*/
        $flight->name = 'San 87065';
        $flight->save();
        $flight->wasChanged();//true
        $flight->wasChanged('name');//true
        $flight->wasChanged('destination');//false


        /**获取原始*/

        $flight->name;//'San 87065'
        $flight->destination;//'San Francisco'

        $flight->name = "New York 77243";
        $flight->name;//New York 77243

        $flight->getOriginal('name');// San 87065

        $flight->getOriginal();//模型原始数组值


    }

    public function replicatingModels(Request $request)
    {
        // You may create an unsaved copy of a model instance using the replicate method.
        // This is particularly useful when you have model instances that share many of the same attributes:
        $flight = Flight::create([
            'type' => 'airbus 5',
            'destination' => 'Victorville',
            'name' => 'Flight 76891'
        ]);

        $flight_2 = $flight->replicate()
            //适用于两个模型数据中，大量的属性相同的情况，replicate会创建一个副本，这时候是没有保存的，
            ->fill([
            'type' => 'boeing 737',
        ]);
        //保存
        $flight_2->save();

    }
}
