<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DefaultOrderByScope implements Scope
{
    protected $column;
    protected $direction;

    /**
     * Create a new DefaultOrderByScope instance.
     *
     * @param string $column
     * @param string $direction
     */
    public function __construct($column = 'id', $direction = 'desc')
    {
        $this->column = $column;
        $this->direction = $direction;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->orderBy($builder->getModel()->getTable() . '.' . $this->column, $this->direction);
    }

}
