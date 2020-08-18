<?php


namespace App\Modules;


use App\Contract\BillingNotifierInterface;

class StripeBiller implements \App\Contract\BillerInterface
{
    /**
     * @var BillingNotifierInterface
     */
    private $notifier;

    public function __construct(BillingNotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function bill(array $user, $amount)
    {
        $this->notifier->notify($user, $amount);
    }
}
