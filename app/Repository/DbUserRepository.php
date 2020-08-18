<?php


namespace App\Repository;


use App\User;

class DbUserRepository implements \App\Contract\UserRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function all()
    {
        return User::all()->toArray();
    }
}
