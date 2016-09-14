<?php

namespace CarterZenk\Slim3\JsonApi\Providers;

use Interop\Container\ContainerInterface;
use NilPortugues\Api\JsonApi\JsonApiTransformer;
use CarterZenk\Slim3\JsonApi\JsonApiSerializer;
use CarterZenk\Slim3\JsonApi\Mapper\Mapper;
use NilPortugues\Api\Mapping\Mapping;
use ReflectionClass;

class Slim3Provider
{
    public function provider()
    {
        return function (ContainerInterface $container) {
            $mappings = $container->get('settings')['jsonapi']['mappings'];

            $mapper = new Mapper($mappings);

            $parsedRoutes = $this->parseRoutes($mapper);

            $transformer = new JsonApiTransformer($parsedRoutes);

            return new JsonApiSerializer($transformer);
        };
    }

    /**
     * @param Mapper $mapper
     *
     * @return Mapper
     */
    protected function parseRoutes(Mapper $mapper)
    {
        foreach ($mapper->getClassMap() as &$mapping) {
            $mappingClass = new ReflectionClass($mapping);

            $this->setUrlWithReflection($mapping, $mappingClass, 'resourceUrlPattern');
            $this->setUrlWithReflection($mapping, $mappingClass, 'selfUrl');
            $mappingProperty = $mappingClass->getProperty('otherUrls');
            $mappingProperty->setAccessible(true);

            $otherUrls = (array) $mappingProperty->getValue($mapping);
            if (!empty($otherUrls)) {
                foreach ($otherUrls as &$url) {
                    if (!empty($url['name'])) {
                        $url = $this->calculateRoute($url);
                    }
                }
            }
            $mappingProperty->setValue($mapping, $otherUrls);

            $this->setJsonApiRelationships($mappingClass, $mapping);
        }

        return $mapper;
    }

    /**
     * @param Mapping         $mapping
     * @param ReflectionClass $mappingClass
     * @param string          $property
     */
    protected function setUrlWithReflection(Mapping $mapping, ReflectionClass $mappingClass, $property)
    {
        $mappingProperty = $mappingClass->getProperty($property);
        $mappingProperty->setAccessible(true);
        $value = $mappingProperty->getValue($mapping);

        if (!empty($value['name'])) {
            $route = $this->calculateRoute($value);
            $mappingProperty->setValue($mapping, $route);
        }
    }

    /**
     * @param ReflectionClass $mappingClass
     * @param                 $mapping
     */
    protected function setJsonApiRelationships(ReflectionClass $mappingClass, $mapping)
    {
        $mappingProperty = $mappingClass->getProperty('relationshipSelfUrl');
        $mappingProperty->setAccessible(true);

        $relationshipSelfUrl = (array) $mappingProperty->getValue($mapping);
        if (!empty($relationshipSelfUrl)) {
            foreach ($relationshipSelfUrl as &$urlMember) {
                if (!empty($urlMember)) {
                    foreach ($urlMember as &$url) {
                        if (!empty($url['name'])) {
                            $url = $this->calculateRoute($url);
                        }
                    }
                }
            }
        }
        $mappingProperty->setValue($mapping, $relationshipSelfUrl);
    }

    /**
     * @param array $value
     *
     * @return mixed|string
     */
    protected function calculateRoute(array $value)
    {
        $route = urldecode(route($value['name']));

        if (!empty($value['as_id'])) {
            preg_match_all('/{(.*?)}/', $route, $matches);
            $route = str_replace($matches[0], '{'.$value['as_id'].'}', $route);
        }

        return $route;
    }
}

