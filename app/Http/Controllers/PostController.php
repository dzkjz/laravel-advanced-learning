<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogPost;
use App\Models\Post;
use App\Rules\UpperCase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    //
    public function create()
    {
        return view('post.create');
    }

    public function store(Request $request)
    {
        $var = '';

        if ($var === 1) {
            // To do this, we will use the validate method provided by the Illuminate\Http\Request object.
            $validatedData = $request->validate(
                [
                    'title' => 'required|unique:posts|max:255',
                    'body' => 'required',
                ]
            );
            // If the validation rules pass, your code will keep executing normally;
            // however, if validation fails,
            // an exception will be thrown and the proper error response will automatically be sent back to the user.

            // In the case of a traditional HTTP request,
            // a redirect response will be generated,
            // while a JSON response will be sent for AJAX requests.
        } elseif ($var === 2) {
            $validatedData = $request->validate([
                'title' => ['required', 'unique:posts', 'max:255'],
                'body' => ['required'],
            ]);
        } elseif ($var === 3) {
            // You may use the validateWithBag method to validate a request and store any error messages within a named error bag:
            $validatedData = $request->validateWithBag('post', [
                'title' => ['required', 'unique:posts', 'max:255'],
                'body' => ['required'],
            ]);
        } elseif ($var === 4) {
            // Sometimes you may wish to stop running validation rules on an attribute after the first validation failure.
            // To do so, assign the bail rule to the attribute:
            $request->validate(
                [
                    'title' => 'bail|required|unique:posts|max:255',
                    // In this example,
                    // if the unique rule on the title attribute fails,
                    // the max rule will not be checked.
                    // Rules will be validated in the order they are assigned.
                    'body' => 'required',
                ]
            );
        } elseif ($var === 5) {
            // If your HTTP request contains "nested" parameters,
            // you may specify them in your validation rules using "dot" syntax:
            $request->validate(
                [
                    'title' => 'required|unique:posts|max:255',
                    'author.name' => 'required',
                    'author.description' => 'required',
                ]
            );
            // The dot notation is for easily accessing array elements, and making their selectors more "fluent".
            // Validating author.name would be the equivalent of having checking the value of the input
            // <input type="text" name="author[name]" />
            //.

        } elseif ($var === 6) {
// In this example, we used a traditional form to send data to the application.
// However, many applications use AJAX requests.
// When using the validate method during an AJAX request, Laravel will not generate a redirect response.
// Instead, Laravel generates a JSON response containing all of the validation errors.
// This JSON response will be sent with a 422 HTTP status code.
        }


    }

    public function storeTest(StoreBlogPost $request)
    {
        $var = '';
        if ($var === 1) {
            // If validation fails, a redirect response will be generated to send the user back to their previous location.
            // The errors will also be flashed to the session so they are available for display.
            // If the request was an AJAX request,
            // a HTTP response with a 422 status code will be returned to
            // the user including a JSON representation of the validation errors.
            $validated = $request->validated();
        } elseif ($var === 2) {
            $auth = $request->authorize();
            if ($auth) {
                //
            }
        } elseif ($var === 3) {

        }

    }

    /***
     * If you do not want to use the validate method on the request,
     * you may create a validator instance manually using the Validator facade.
     * The make method on the facade generates a new validator instance:
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storeTest2(Request $request)
    {
        $validator = Validator::make(
            $request->all(),//The first argument passed to the make method is the data under validation.
            [
                'title' => 'required|unique:posts|max:255',
                'body' => 'required',
            ]//The second argument is the validation rules that should be applied to the data.
        );
        if ($validator->fails()) {
            return redirect('post/create')
                ->withErrors($validator) //After checking if the request validation failed,
                // you may use the withErrors method to flash the error messages to the session.
                // When using this method,
                // the $errors variable will automatically be shared with your views after redirection,
                // allowing you to easily display them back to the user.
                // The withErrors method accepts a validator, a MessageBag, or a PHP array.
                ->withInput();
        }
        // Store the blog post...

        // If you have multiple forms on a single page,
        // you may wish to name the MessageBag of errors,
        // allowing you to retrieve the error messages for a specific form.
        // Pass a name as the second argument to withErrors:
        return redirect('register')->withErrors($validator, 'login');
        // You may then access the named MessageBag instance from the $errors variable:
        // {{ $errors->login->first('email') }}
    }

    /**
     * If you would like to create a validator instance manually
     * but still take advantage of the automatic redirection offered by the request's validate method,
     * you may call the validate method on an existing validator instance.
     * If validation fails, the user will automatically be redirected or,
     * in the case of an AJAX request, a JSON response will be returned:
     * @param Request $request
     */
    public function storeTest3(Request $request)
    {
        $var = '';
        if ($var === 1) {
            Validator::make($request->all(),
                [
                    'title' => 'required|unique:posts|max:255',
                    'body' => 'required',
                ])->validate();

        } elseif ($var === 2) {
            // You may use the validateWithBag method to store the error messages in a named error bag if validation fails:
            Validator::make($request->all(), [
                'title' => 'required|unique:posts|max:255',
                'body' => 'required',
            ])->validateWithBag('post');
        } elseif ($var === 3) {
            $validator = Validator::make($request->all(), []);

            // The validator also allows you to attach callbacks to be run after validation is completed.
            // This allows you to easily perform further validation and even add more error messages to the message collection.
            // To get started, use the after method on a validator instance:
            $validator->after(function ($validator) {
                if ($this->somethingElseIsInvalid()) {
                    $validator->errors()->add('field', 'something is wrong with this field');
                }
            });
            if ($validator->fails()) {

                // After calling the errors method on a Validator instance,
                // you will receive an Illuminate\Support\MessageBag instance,
                // which has a variety of convenient methods for working with error messages.
                // The $errors variable that is automatically made available to all views is also an instance of the MessageBag class.
                $errors = $validator->errors();

                // To retrieve the first error message for a given field, use the first method:
                echo $errors->first('email');

                // If you need to retrieve an array of all the messages for a given field, use the get method:
                foreach ($errors->get('email') as $message) {
                    echo $message;
                }
                // If you are validating an array form field,
                // you may retrieve all of the messages for each of the array elements using the * character:

                foreach ($errors->get('attachments.*') as $message) {
                    echo $message;
                }

                //To retrieve an array of all messages for all fields, use the all method:
                foreach ($errors->all() as $message) {
                    echo $message;
                }

                //The has method may be used to determine if any error messages exist for a given field:
                if ($errors->has('email')) {
                    //
                }


            }
        } elseif ($var === 4) {
            //If needed, you may use custom error messages for validation instead of the defaults.
            // There are several ways to specify custom messages.
            // First, you may pass the custom messages as the third argument to the Validator::make method:
            $input = $request->all();

            $rules = [];
            $messages =
                [
                    'required' => 'The :attribute field is required',
                ];
            // In this example,
            // the :attribute placeholder will be replaced by the actual name of the field under validation.
            // You may also utilize other placeholders in validation messages. For example:
//            $messages = [
//                'same' => 'The :attribute and :other must match.',
//                'size' => 'The :attribute must be exactly :size.',
//                'between' => 'The :attribute value :input is not between :min - :max.',
//                'in' => 'The :attribute must be one of the following types: :values',
//            ];
            Validator::make($input, $rules, $messages);
        } elseif ($var === 5) {
            // Sometimes you may wish to specify a custom error message only for a specific field.
            // You may do so using "dot" notation. Specify the attribute's name first, followed by the rule:
            $messages =
                [
                    'email.required' => 'We need to know your e-mail address!',
                ];
            // In most cases, you will probably specify your
            // custom messages in a language file instead of passing them directly to the Validator.
            // To do so, add your messages to custom array in the resources/lang/xx/validation.php language file.


        } elseif ($var === 6) {


            //You may also pass the custom attributes as the fourth argument to the Validator::make method:

            $input = $request->all();

            $rules = [];
            $messages =
                [
                    'required' => 'The :attribute field is required',
                ];

            $customAttributes = [
                'email' => 'email address',
            ];

            Validator::make($input, $rules, $messages, $customAttributes);

        } elseif ($var === 7) {

            // Sometimes you may need the :value portion of your validation message
            // to be replaced with a custom representation of the value.
            // For example,
            // consider the following rule that specifies that a credit card number
            // is required if the payment_type has a value of cc:

            $request->validate(
                [
                    'credit_card_number' => 'required_if:payment_type,cc',
                ]
            );
            // If this validation rule fails, it will produce the following error message:

            // The credit card number field is required when payment type is cc.


            // Instead of displaying cc as the payment type value,
            // you may specify a custom value representation in your validation language file by defining a values array:


            //
        } elseif ($var === 8) {
            $request->validate(
                [
                    // Once the rule has been defined, you may attach it to a validator
                    // by passing an instance of the rule object with your other validation rules:
                    'name' => ['required', 'string', new UpperCase],
                ]
            );
        } elseif ($var === 9) {
            // If you only need the functionality of a custom rule once throughout your application,
            // you may use a Closure instead of a rule object.
            // The Closure receives the attribute's name,
            // the attribute's value, and a $fail callback that should be called if validation fails:

            $validator = Validator::make($request->all(), [
                'title' => [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) {
                        if ($value === 'foo') {
                            $fail($attribute . 'is invalid');
                        }
                    }
                ]
            ]);
        } elseif ($var === 10) {

        }


    }

    private function somethingElseIsInvalid()
    {

        return function () {
            return false;
        };
    }

    public function isValid($value)
    {
        // Sometimes you may need to report an exception but continue handling the current request.
        // The report helper function allows you to quickly report an exception
        // using your exception handler's report method without rendering an error page:
        try {
            // Validate the value
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }

    public function showProfile($id)
    {
        $var = '';
        if ($var === 1) {
            Log::info('Showing user profile for user: ' . $id);

            $user = User::findOrFail($id);

            Log::info('User failed to login.', ['id' => $user->id]);

            return view('user.profile', ['user' => $user]);
        } elseif ($var === 2) {

            // Sometimes you may wish to log a message to a channel other than your application's default channel.
            // You may use the channel method on the Log facade to retrieve
            // and log to any channel defined in your configuration file:

            Log::channel('slack')->info('Something happened!');
        } elseif ($var === 3) {

            // If you would like to create an on-demand logging stack
            // consisting of multiple channels, you may use the stack method:

            Log::stack(['single', 'slack'])->info('Something happened!');
        }
    }

    public function localeTest()
    {
        App::getLocale();
        App::setLocale();

    }

    public function authorizingActions($post)
    {
        // To authorize an action using gates, you should use the allows or denies methods.
        // Note that you are not required to pass the currently authenticated user to these methods.
        // Laravel will automatically take care of passing the user into the gate Closure:
        if (Gate::allows('edit-settings')) {
            // The Current user can edit settings
        }
        if (Gate::allows('update-post', $post)) {
            // The Current user can update the post
        }

        if (Gate::denies('update-post', $post)) {
            // The current user can't update the post
        }

        // If you would like to determine if a particular user is authorized to perform an action,
        // you may use the forUser method on the Gate facade:
        $user = \App\Models\User::find(1);
        if (Gate::forUser($user)->allows('update-post', $post)) {
            // The $user can update the post...
        }

        if (Gate::forUser($user)->denies('update-post', $post)) {
            // The $user can't update the post...
        }

        // You may authorize multiple actions at a time with the any or none methods:
        if (Gate::any(['update-post', 'delete-post'], $post)) {
            // The current user can update or delete post...
        }

        if (Gate::none(['update-post', 'delete-post'], $post)) {
            // Current user can't update and delete post...
        }

        // If you would like to attempt to authorize an action and automatically throw an
        // Illuminate\Auth\Access\AuthorizationException if the user is not allowed to perform the given action,
        // you may use the Gate::authorize method.
        // Instances of AuthorizationException are automatically converted to 403 HTTP response:
        Gate::authorize('update-post', $post);
        // The action is authorized...


        // The gate methods for authorizing abilities (allows, denies, check, any, none, authorize, can, cannot)
        // and the authorization Blade directives (@can, @cannot, @canany) can receive an array as the second argument.
        // These array elements are passed as parameters to gate,
        // and can be used for additional context when making authorization decisions:

        $category = [];
        $extraFlag = true;
        if (Gate::check('create-post', [$category, $extraFlag])) {
            // The user can create the post...
        }

    }

    public function gateResponses($post)
    {
        // When returning an authorization response from your gate,
        // the Gate::allows method will still return a simple boolean value;
        // however, you may use the Gate::inspect method to get the full authorization response returned by the gate:
        $response = Gate::inspect('edit-settings', $post);

        if ($response->allowed()) {
            // The action is authorized...
        } else {
            echo $response->message();
        }

        // Of course, when using the Gate::authorize method to
        // throw an AuthorizationException if the action is not authorized,
        // the error message provided by the authorization response will be propagated to the HTTP response:

        Gate::authorize('edit-settings', $post);

        // The action is authorized...

    }


    public function interceptionGateChecks()
    {
        // Sometimes, you may wish to grant all abilities to a specific user.
        // You may use the before method to define a callback that is run before all other authorization checks:
        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
        // If the before callback returns a non-null result that result will be considered the result of the check.

        // You may use the after method to define a callback to be executed after all other authorization checks:
        Gate::after(function ($user, $ability, $result, $arguments) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        // Similar to the before check,
        // if the after callback returns a non-null result that result will be considered the result of the check.

    }

    public function policyResponses(Post $post)
    {
        // When returning an authorization response from your policy,
        // the Gate::allows method will still return a simple boolean value;
        // however, you may use the Gate::inspect method to get the full authorization response returned by the gate:
        $response = Gate::inspect('update', $post);

        if ($response->allowed()) {
            // The action is authorized...

        } else {
            echo $response->message();
        }
        // Of course, when using the Gate::authorize method to throw an AuthorizationException if the
        // action is not authorized, the error message provided by the authorization
        // response will be propagated to the HTTP response:
        //
        Gate::authorize('update', $post);

        // The action is authorized...
    }

    public function updatePost(Request $request, Post $post)
    {
        $user = $request->user();

        // The User model that is included with your Laravel application
        // includes two helpful methods for authorizing actions: can and cant.
        // The can method receives the action you wish to authorize and the relevant model.
        // For example, let's determine if a user is authorized to update a given Post model:

        if ($user->can('update', $post)) {
            //
        }

        // If a policy is registered for the given model,
        // the can method will automatically call the appropriate policy and return the boolean result.
        // If no policy is registered for the model,
        // the can method will attempt to call the Closure based Gate matching the given action name.


        // Remember, some actions like create may not require a model instance.
        // In these situations, you may pass a class name to the can method.


        if (
        $user->can(
            'create',
            Post::class  //The class name will be used to determine which policy to use when authorizing the action:
        )
        ) {
            // Executes the "create" method on the relevant policy...
        }

        // In addition to helpful methods provided to the User model,
        // Laravel provides a helpful authorize method to any of your controllers
        // which extend the App\Http\Controllers\Controller base class.
        // Like the can method, this method accepts the name of the action you wish to authorize and the relevant model.
        $this->authorize('update', $post);
        // If the action is not authorized,
        // the authorize method will throw an Illuminate\Auth\Access\AuthorizationException,
        // which the default Laravel exception handler will convert to an HTTP response with a 403 status code:


        // As previously discussed, some actions like create may not require a model instance.
        // In these situations, you should pass a class name to the authorize method.
        // The class name will be used to determine which policy to use when authorizing the action:
        $this->authorize('create', Post::class);


        // If you are utilizing resource controllers,
        // you may make use of the authorizeResource method in the controller's constructor.
        // This method will attach the appropriate can middleware definitions to the resource controller's methods.

        // The authorizeResource method accepts the model's class name as its first argument,
        // and the name of the route / request parameter that will contain the model's ID as its second argument.

//        $this->authorizeResource(Post::class, 'post');

        // You should ensure your resource controller is created with the --model flag
        // to have the required method signatures and type hints: https://laravel.com/docs/master/authorization#via-controller-helpers


        // You may use the make:policy command with the --model option to quickly
        // generate a policy class for a given model: php artisan make:policy PostPolicy --model=Post.


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
    public function updateSupplyingAdditionalContext(Request $request, Post $post)
    {
        // When attempting to determine if the authenticated user can update a given post,
        // we can invoke this policy method like so:

        $this->authorize('updateSupplyingAdditionalContext', [$post, $request->input('category')]);

        // The current user can update the blog post...
    }


}
