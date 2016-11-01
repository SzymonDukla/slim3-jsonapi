<?php

namespace CarterZenk\Slim\JsonApi\Schema;

use Illuminate\Database\Eloquent\Model;
use Neomerx\JsonApi\Schema\SchemaProvider;

class EloquentSchemaProvider extends SchemaProvider
{
    /**
     * Get resource identity.
     *
     * @param object $resource
     * @return string
     * @throws \Exception
     */
    public function getId($resource)
    {
        if ($resource instanceof Model) {
            return (string) $resource->getKey();
        } else {
            throw new \Exception('Resource is not an Eloquent model.');
        }
    }

    /**
     * Get resource attributes.
     *
     * @param object $resource
     * @return array
     * @throws \Exception
     */
    public function getAttributes($resource)
    {
        if ($resource instanceof Model) {
            return $resource->getAttributes();
        } else {
            throw new \Exception('Resource is not an Eloquent model.');
        }
    }

}