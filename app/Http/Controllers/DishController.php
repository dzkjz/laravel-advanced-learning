<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function getDishablesOfDish(Dish $dish)
    {
        $dishables = $dish->teachers;
        if (!$dishables) {
            $dishables = $dish->students;
        }

        if (!$dishables) {
            abort(404);
        }

        foreach ($dishables as $dishable) {
            echo $dishable->id;
        }
    }
}
