<?php

namespace LocalheroPortal\LLI\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

class ThroughLead extends Relation
{
    public function addConstraints()
    {
        if (static::$constraints) {
            $this->query->where([
                ['commentable_id', $this->parent->id],
                ['commentable_type', 'company']
            ]);
            if (isset($this->parent->lead_id)) {
                $this->query->orWhere([
                ['commentable_id', $this->parent->lead_id],
                ['commentable_type', 'lead']
            ]);
            }
        }
    }

    /**
     * Initialize the relation on a set of models.
     *
     * @param  array   $models
     * @param  string  $relation
     * @return array
     */
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->related->newCollection());
        }

        return $models;
    }

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param  array   $models
     * @param  \Illuminate\Database\Eloquent\Collection  $results
     * @param  string  $relation
     * @return array
     */
    public function match(array $models, Collection $results, $relation)
    {
        return $this->matchMany($models, $results, $relation);
    }

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        return $this->query->get();
    }

    /**
     * Set the constraints for an eager load of the relation.
     *
     * @param  array  $models
     * @return void
     */
    public function addEagerConstraints(array $models)
    {
        // TO DO
    }
}
