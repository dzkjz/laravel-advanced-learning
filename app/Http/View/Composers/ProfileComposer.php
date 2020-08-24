<?php


namespace App\Http\View\Composers;


use App\Repository\UserRepository;
use Illuminate\View\View;


class ProfileComposer
{
    /**
     *  The user repository implementation
     * @var UserRepository
     */
    protected $users;


    /**
     *  Create a new profile composer.
     * ProfileComposer constructor.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        // All view composers are resolved via the service container,
        // so you may type-hint any dependencies you need within a composer's constructor.
        $this->users = $users;
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        // Just before the view is rendered, the composer's compose method is called with the Illuminate\View\View instance.
        // You may use the with method to bind data to the view.
        $view->with('count', $this->users->count());
    }

}
