<?php

namespace App\Rules;

// If you would like a rule object to run when an attribute is empty,
// you should implement the Illuminate\Contracts\Validation\ImplicitRule interface.
// This interface serves as a "marker interface" for the validator;
// therefore, it does not contain any methods you need to implement.
use Illuminate\Contracts\Validation\ImplicitRule;

use Illuminate\Contracts\Validation\Rule;

class UpperCase implements Rule, ImplicitRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     * The passes method receives the attribute value and name,
     * and should return true or false depending on whether the attribute value is valid or not.
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        return strtoupper($value) === $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
//        return 'The :attribute must be uppercase.';
        return trans('validation.uppercase');
    }


}
