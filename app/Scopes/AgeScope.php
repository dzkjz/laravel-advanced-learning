<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Writing a global scope is simple. Define a class that implements the Illuminate\Database\Eloquent\Scope interface.
 *
 * Class AgeScope
 * @package App\Scopes
 */
class AgeScope implements Scope
{

    /** This interface requires you to implement one method: apply.
     * The apply method may add where constraints to the query as needed:
     * @param Builder $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('age', '>', 200)
            // If your global scope is adding columns to the select clause of the query,
            // you should use the addSelect method instead of select.
            // This will prevent the unintentional replacement of the query's existing select clause.
            ->addSelect([]);

    }
}
