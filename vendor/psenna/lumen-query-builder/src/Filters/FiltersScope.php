<?php

namespace Spatie\QueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class FiltersScope implements Filter
{
    public function __invoke(Builder $query, $values, string $property) : Builder
    {
        $scope = Str::camel($property);
        $values = Arr::wrap($values);

        return $query->$scope(...$values);
    }
}
