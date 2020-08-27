<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param $user
     * @param $post
     * @return bool
     */
    public function delete($user, $post)
    {
        return $user->id === $post->user_id;
    }

    /**
     * @param User $user
     * @param Post $post
     * @return bool|Response
     */
    public function update(User $user, Post $post)
    {
        $needDetailedResponse = true;
        $original = $user->id === $post->user_id;
        if (!$needDetailedResponse) {
            return $original;
        }
        // So far, we have only examined policy methods that return simple boolean values.
        // However, sometimes you may wish to return a more detailed response, including an error message.
        // To do so, you may return an Illuminate\Auth\Access\Response from your policy method:
        return $original ? Response::allow() : Response::deny('You do not own this post');
    }

    /**
     * Some policy methods only receive the currently authenticated user and not an instance of the model they authorize.
     * This situation is most common when authorizing create actions. For example, if you are creating a blog,
     * you may wish to check if a user is authorized to create any posts at all.
     *
     * When defining policy methods that will not receive a model instance
     *
     * It will not receive a model instance.
     * Instead, you should define the method as only expecting the authenticated user:
     * @param User $user
     */
    public function create(User $user)
    {
        //Determine if the given user can create posts.
    }

    /**
     * By default, all gates and policies automatically return false if the incoming HTTP request was not initiated
     * by an authenticated user.
     * However, you may allow these authorization checks to pass through to your gates and policies by
     * declaring an "optional" type-hint or supplying a null default value for the user argument definition:
     * @param User|null $user
     * @param Post $post
     * @return bool
     */
    public function updateAllowUnauthenticatedUser(?User $user, Post $post)
    {
        return optional($user)->id === $post->user_id;
    }

    /**
     * For certain users, you may wish to authorize all actions within a given policy.
     * To accomplish this, define a before method on the policy.
     * The before method will be executed before any other methods on the policy,
     * giving you an opportunity to authorize the action before the intended policy method is actually called.
     * This feature is most commonly used for authorizing application administrators to perform any action:
     *
     * @param $user
     * @param $ability
     * @return bool|null
     *
     * The before method of a policy class will not be called if the class doesn't contain a method with
     * a name matching the name of the ability being checked.
     *
     */
    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        } else {
            // If you would like to deny all authorizations for a user you should return false from the before method.
            // If null is returned, the authorization will fall through to the policy method.
        }
    }

    /**
     *  When authorizing actions using policies,
     * you may pass an array as the second argument to the various authorization functions and helpers.
     * The first element in the array will be used to determine which policy should be invoked,
     * while the rest of the array elements are passed as parameters to the policy method and
     * can be used for additional context when making authorization decisions.
     * For example,
     * consider the following PostPolicy method definition which contains an additional $category parameter:
     */
    public function updateSupplyingAdditionalContext(User $user, Post $post, int $category)
    {
        return $user->id === $post->user_id &&
            $category > 3;
    }



}
