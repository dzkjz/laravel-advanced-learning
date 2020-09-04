<?php


namespace App;


use Illuminate\Contracts\Support\Arrayable;


class Address implements Arrayable, \JsonSerializable
//If you plan to serialize your Eloquent models containing value objects to JSON or arrays,
// you should implement the Illuminate\Contracts\Support\Arrayable and JsonSerializable interfaces on the value object.
{
    public $lineOne;

    public $lineTwo;

    public function __construct($address_line_one, $address_line_two)
    {
        $this->lineOne = $address_line_one;
        $this->lineTwo = $address_line_two;
    }

    public function toArray()
    {
        return [
            'lineOne' => $this->lineOne,
            'lineTwo' => $this->lineTwo,
        ];
    }

    public function jsonSerialize()
    {
        return json_decode($this->toArray());
    }
}
