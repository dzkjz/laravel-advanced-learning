<?php


namespace App\Contract;


interface BillerInterface
{
    public function bill(array $user, $amount);
}
