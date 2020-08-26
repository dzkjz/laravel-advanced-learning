<?php

namespace App\Http\Requests;

use App\Models\Comment;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Psy\Util\Str;

class StoreBlogPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $comment = Comment::find(
            $this->
            route('comment') //This method grants you access to the URI parameters defined on the route being called,
        //such as the {comment} parameter in the example below:
        //Route::post('comment/{comment}');
        );
        return $comment && $this->user()->can('update', $comment);
        // If the authorize method returns false,
        // a HTTP response with a 403 status code will automatically be returned
        // and your controller method will not execute.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = auth()->user();

        return [
            //
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
            'avatar' => [
                'required',
                Rule::dimensions()->maxHeight(500)->maxWidth(1000)->ratio(3 / 2),
            ],
            'email' =>
                [
                    'required',
//                    Rule::unique('users')->ignore($user),
// You should never pass any user controlled request input into the ignore method.
// Instead,
// you should only pass a system generated unique ID such as an auto-incrementing ID or UUID from an Eloquent model instance.
// Otherwise, your application will be vulnerable to an SQL injection attack.
//                    Rule::unique('users')->ignore($user->id),

                    //However, you may pass a different column name as the second argument to the unique method:
                    Rule::unique('users', 'email_address')->ignore($user->id, 'user_id'),
                    Rule::unique('users')->where(function ($query) {
                        return $query->where('account_id', 1);
                    }),
                ]
        ];
    }

    /***
     * If you would like to add an "after" hook to a form request,
     * you may use the withValidator method.
     * This method receives the fully constructed validator,
     * allowing you to call any of its methods before the validation rules are actually evaluated:
     *
     * @FormRequest 内部在调用
     * @param Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            if ($this->somethingElseIsInvalid()) {
                $validator->errors()->add('filed', 'Something is wrong with this field');
            }
        });
    }

    private function somethingElseIsInvalid()
    {
        return true;
    }

    /**
     * You may customize the error messages used by the form request by overriding the messages method.
     * This method should return an array of attribute / rule pairs and their corresponding error messages:
     * @return array
     */
    public function messages()
    {
        return
            [
                'title.required' => Lang::get('A title is required'),
                'body.required' => Lang::get('A message is required'),
            ];
    }

    /***
     * If you would like the :attribute portion of your validation message to be replaced with a custom attribute name,
     * you may specify the custom names by overriding the attributes method.
     * This method should return an array of attribute / name pairs:
     *
     * @return array|string[]
     */
    public function attributes()
    {
        return
            [
                'email' => 'email address',

            ];
    }

    /**
     * If you need to sanitize any data from the request before you apply your validation rules,
     * you can use the prepareForValidation method:
     */
    public function prepareForValidation()
    {
        $this->merge(
            [
                'slug' => \Illuminate\Support\Str::slug($this->slug),
                'nameUpper' => strtoupper($this->get('name')),
            ]
        );
    }


}
