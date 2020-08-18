<?php


namespace App\Contract;


interface BillingNotifierInterface
{
    public function notify(array $user, $amount);
}
