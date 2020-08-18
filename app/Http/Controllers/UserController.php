<?php

namespace App\Http\Controllers;

use App\Contract\UserRepositoryInterface;
use App\Modules\StripeBiller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    public function getIndex()
    {
//        $reflection = new \ReflectionClass(StripeBiller::class);
//        dd($reflection->getMethods());
//        dd($reflection->getNamespaceName());
//        dd($reflection->getProperties());

        $users = $this->users->all();
        return View::make('users.index', compact('users'));
    }
}
