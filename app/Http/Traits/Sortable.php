<?php

namespace App\Http\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

trait Sortable
{
    /**
     * Scope to handle complex dynamic ordering including relationships.
     */
    public function scopeDynamicOrder(Builder $query, Request $request): Builder
    {
        $column = $request->order_by;
        $direction = $request->get('direction', 'asc');

        if (!$column) {
            return $query;
        }

        // // Clear the DefaultOrderByScope before applying the new one
        // $query->reorder(); 

        if (str_contains($column, '.')) {
            [$relationName, $relColumn] = explode('.', $column);

            if (method_exists($this, $relationName)) {
                $relation = $this->$relationName();

                if ($relation instanceof BelongsTo) {
                    $relatedModel = $relation->getRelated();
                    
                    $selectExpression = $this->resolveSortColumn($relatedModel, $relColumn);

                    return $query->orderBy(
                        $relatedModel->newQuery()
                            ->selectRaw($selectExpression)
                            ->whereColumn(
                                $relatedModel->getQualifiedKeyName(),
                                $relation->getQualifiedForeignKeyName()
                            ),
                        $direction
                    );
                }
            }
        }

        return $query->orderBy($this->getTable() . '.' . $column, $direction);
    }

    /**
     * Resolve the SQL column/expression based on the model type.
     */
    protected function resolveSortColumn($relatedModel, string $relColumn): string
    {

        if ($relatedModel instanceof User && $relColumn === 'name') {
            return "CONCAT(first_name, ' ', last_name)";
        }elseif ($relColumn === 'name') {
            return "name_" . app()->getLocale();
        }

        return $relColumn;
    }
}
