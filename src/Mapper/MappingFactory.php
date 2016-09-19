<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 10/16/15
 * Time: 8:59 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CarterZenk\Slim3\JsonApi\Mapper;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model;
use NilPortugues\Api\Mapping\Mapping;
use NilPortugues\Api\Mapping\MappingException;
use ReflectionClass;

/**
 * Class MappingFactory.
 */
class MappingFactory extends \NilPortugues\Api\Mapping\MappingFactory
{
    /**
     * @var array
     */
    protected static $eloquentClasses = [];

    /**
     * @param string $className
     *
     * @return array
     */
    protected static function getClassProperties($className)
    {
        if (\class_exists($className, true)) {
            $reflection = new ReflectionClass($className);
            $value = $reflection->newInstanceWithoutConstructor();

            if (\is_subclass_of($value, Model::class, true)) {
                $attributes = Manager::schema()->getColumnListing($value->getTable());

                self::$eloquentClasses[$className] = $attributes;
            }
        }

        return (!empty(self::$eloquentClasses[$className])) ? self::$eloquentClasses[$className] : parent::getClassProperties($className);
    }

    protected static function setRequiredProperties(array &$mappedClass, Mapping $mapping, $className)
    {
        if (false === empty($mappedClass[static::REQUIRED_PROPERTIES_KEY])) {
            $mapping->setRequiredProperties($mappedClass[static::REQUIRED_PROPERTIES_KEY]);
            foreach ($mapping->getRequiredProperties() as $propertyName) {
                if (false === \in_array($propertyName, static::getClassProperties($className), true)) {
                    throw new MappingException(
                        \sprintf(
                            'Could not add required property %s in class %s because it does not exist.',
                            $propertyName,
                            $className
                        )
                    );
                }
            }
        }
    }
}
