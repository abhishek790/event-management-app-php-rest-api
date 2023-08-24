<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
// now you can't import 2 classes with the same name so we have to use an alias
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

// we can resue this optionally loading relation code for other actions other than index inside the event controller and to create this kind of common logic we can create a so called trait
// trait are added to classes and used within classes,once trait is used within the class all the methods from a trait will be added to the class.It lets you add some additional fucntionlity to the class without using inheritance

trait CanLoadRelationsips
{
    // here in the argument it will either have the model or the querybuilder instance and then we have to pass the name of the relationship to load
    // so let's pass first argument as for where we load the relationships for something either modal or a querybuilder.Now there are two types of builder query and eloquent,we load both,

    public function loadRelationship(
        Model|QueryBuilder|EloquentBuilder $for,
        array $relations
    ) {
        foreach ($relations as $relation) {
            $for->when(
                $this->shouldIncludeRelation($relation),
                fn($q) => $q->with($relation)
            );
        }

    }
}

function shouldIncludeRelation(string $relation): bool
{

    $include = request()->query('include');

    if (!$include) {
        return false;
    }

    $relations = array_map('trim', explode(',', $include));

    return in_array($relation, $relations);

}