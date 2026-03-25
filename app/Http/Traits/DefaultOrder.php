<?php


namespace App\Http\Traits;


use App\Scopes\DefaultOrderByScope;

trait DefaultOrder
{

    protected static function bootDefaultOrder()
    {
        $column = static::$defaultOrder['column'] ?? 'id';
        $direction = static::$defaultOrder['direction'] ?? 'desc';

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
