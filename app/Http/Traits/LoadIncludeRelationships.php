<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;

trait LoadIncludeRelationships
{
    public function loadRelationships(
        Model|EloquentBuilder|QueryBuilder $for,
        ?array $relations = null
    ): Model|EloquentBuilder|QueryBuilder
    {
        $relations = $relations ?? $this->relations ?? [];

        foreach ($relations as $relation) {
            $for->when(
                $this->includeRelation($relation),
                fn($q) => $for instanceof Model ? ($for->load($relation)) : ($q->with($relation))
            );
        };

        return $for;
    }


    protected function includeRelation(string $relation): bool
    {
        $include = request()->query('include');

        if (!$include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include));

        return in_array($relation, $relations);
    }
}
