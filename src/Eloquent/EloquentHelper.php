<?php

namespace CarterZenk\Slim3\JsonApi\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use NilPortugues\Api\JsonApi\Http\Request\Request;
use CarterZenk\Slim3\JsonApi\JsonApiSerializer;

/**
 * Class EloquentHelper.
 */
class EloquentHelper
{
    /**
     * @param Request $request
     * @param JsonApiSerializer $serializer
     * @param Builder $builder
     * @param int $pageSize
     * @return Builder
     */
    public function paginate(Request $request, JsonApiSerializer $serializer, Builder $builder, $pageSize = null)
    {
        $this->sort($request, $serializer, $builder, $builder->getModel());
        $filters = $this->extractFilters($request);

        $builder->where($filters)->paginate(
            $request->getPage()->size() ?: $pageSize,
            $this->columns($serializer, $request->getFields()->get()),
            'page',
            $request->getPage()->number()
        );

        return $builder;
    }

    /**
     * @param Request $request
     * @param JsonApiSerializer $serializer
     * @param Builder $builder
     * @param Model $model
     * @return Builder
     */
    protected function sort(Request $request, JsonApiSerializer $serializer, Builder $builder, Model $model)
    {
        $mapping = $serializer->getTransformer()->getMappingByClassName(get_class($model));
        $sorts = $request->getSort()->sorting();

        if (!empty($sorts)) {
            $aliased = $mapping->getAliasedProperties();

            $sortsFields = str_replace(array_values($aliased), array_keys($aliased), array_keys($sorts));
            $sorts = array_combine($sortsFields, array_values($sorts));

            foreach ($sorts as $field => $direction) {
                $builder->orderBy($field, ($direction === 'ascending') ? 'ASC' : 'DESC');
            }
        }

        return $builder;
    }

    /**
     * @param JsonApiSerializer $serializer
     * @param array             $fields
     *
     * @return array
     */
    protected function columns(JsonApiSerializer $serializer, array $fields)
    {
        $filterColumns = [];

        foreach ($serializer->getTransformer()->getMappings() as $mapping) {
            $classAlias = $mapping->getClassAlias();

            if (!empty($fields[$classAlias])) {
                $className = $mapping->getClassName();
                $aliased = $mapping->getAliasedProperties();

                /** @var \Illuminate\Database\Eloquent\Model $model * */
                $model = new $className();
                $columns = $fields[$classAlias];

                if (count($aliased) > 0) {
                    $columns = str_replace(array_values($aliased), array_keys($aliased), $columns);
                }

                foreach ($columns as &$column) {
                    $filterColumns[] = sprintf('%s.%s', $model->getTable(), $column);
                }
                $filterColumns[] = sprintf('%s.%s', $model->getTable(), $model->getKeyName());
            }
        }

        return (count($filterColumns) > 0) ? $filterColumns : ['*'];
    }

    protected function extractFilters(Request $request)
    {
        $filters = [];

        foreach($request->getFilters() as $filterKey => $filterValue) {
            $filters[] = [$filterKey, '=', $filterValue];
        }

        return $filters;
    }
}
