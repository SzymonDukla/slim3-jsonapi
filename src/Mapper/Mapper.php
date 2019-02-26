<?php

namespace SzymonDukla\Slim3\JsonApi\Mapper;

class Mapper extends \AmaranthCloud\Api\Mapping\Mapper
{
    /**
     * @param string|array $mappedClass
     *
     * @return \AmaranthCloud\Api\Mapping\Mapping
     */
    protected function buildMapping($mappedClass)
    {
        return (\is_string($mappedClass) && \class_exists($mappedClass, true)) ?
            MappingFactory::fromClass($mappedClass) :
            MappingFactory::fromArray($mappedClass);
    }
}
