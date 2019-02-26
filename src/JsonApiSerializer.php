<?php

namespace SzymonDukla\Slim3\JsonApi;

use NilPortugues\Serializer\Drivers\Eloquent\EloquentDriver;
use NilPortugues\Api\JsonApi\JsonApiSerializer as Serializer;

/**
 * Class JsonApiSerializer.
 */
class JsonApiSerializer extends Serializer
{
    /**
     * Extract the data from an object.
     *
     * @param mixed $value
     *
     * @return array
     */
    protected function serializeObject($value)
    {
        $serialized = EloquentDriver::serialize($value);

        return ($value !== $serialized) ? $serialized : parent::serializeObject($value);
    }
}
