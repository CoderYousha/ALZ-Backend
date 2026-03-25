<?php


namespace App\Http\Traits;


use App\Scopes\DefaultOrderByScope;

trait ContentOrder
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        $column = static::$defaultOrder['column'] ?? 'order';
        $direction = static::$defaultOrder['direction'] ?? 'asc';

        static::addGlobalScope(new DefaultOrderByScope($column, $direction));
    }

    /**
     *  Ignore defualt order scope
     */
    public function scopeIgnoreOrderScope($query)
    {
        return $query->withoutGlobalScope(DefaultOrderByScope::class);
    }

}
