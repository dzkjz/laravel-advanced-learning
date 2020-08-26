<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogPost;
use App\Rules\UpperCase;
use Illuminate\Http\Request;
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
}
