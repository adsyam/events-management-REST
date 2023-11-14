<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

trait CanLoadRelationships
{
    /**
     * Eager load relationships for a given model or query.
     *
     * @param Model|QueryBuilder|EloquentBuilder|HasMany $for The model or query to load relationships for.
     * @param array|null $relations The relationships to be loaded.
     * @return Model|QueryBuilder|EloquentBuilder|HasMany The model or query with loaded relationships.
     */
    public function loadRelationships(
        Model|QueryBuilder|EloquentBuilder|HasMany $for,
        ?array $relations = null
    ): Model|QueryBuilder|EloquentBuilder|HasMany {
        // If $relations is null, assign $this->relations; otherwise, use an empty array
        $relations = $relations ?? $this->relations ?? [];
    
        // Iterate over each relation
        foreach ($relations as $relation) {
            // Check if the relation should be included
            $for->when(
                $this->shouldIncludeRelation($relation),
                fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
            );
        }
    
        // Return the model or query with loaded relationships
        return $for;
    }

    /**
     * Check if a given relation should be included based on the "include" query parameter.
     *
     * @param string $relation The relation to check.
     * @return bool Whether the relation should be included or not.
     */
    protected function shouldIncludeRelation(string $relation): bool {
        // Get the value of the "include" query parameter from the current request
        $include = request()->query('include');
    
        // If the "include" value is empty, the relation should not be included
        if (!$include) {
            return false;
        }
    
        // Split the "include" value into an array of relations and trim each relation
        $relations = array_map('trim', explode(',', $include));
    
        // Check if the given relation is present in the array of relations
        // Return true if it is, indicating that the relation should be included; otherwise, return false
        return in_array($relation, $relations);
    }
}