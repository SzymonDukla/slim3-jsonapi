<?php

namespace CarterZenk\Slim\JsonApi\Http\Request\Parameters;

class Sort
{
    /**
     * @var array
     */
    protected $sort = [];

    /**
     * @param string $field
     * @param string $direction
     */
    public function addField($field, $direction)
    {
        $this->sort[(string) $field] = (string) $direction;
    }

    /**
     * @return array
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return array_keys($this->sort);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === count($this->sort);
    }
}
