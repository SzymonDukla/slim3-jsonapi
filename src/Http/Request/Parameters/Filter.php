<?php

namespace CarterZenk\Slim\JsonApi\Http\Request\Parameters;

class Filter
{
    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @param string $attribute
     * @param string $value
     */
    public function addFilter($attribute, $value)
    {
        $this->filters[(string) $attribute][] = $value;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return string[]
     */
    public function getAttributes()
    {
        return array_keys($this->filters);
    }

    /**
     * @param string $attribute
     *
     * @return array
     */
    public function getValues($attribute)
    {
        return (array_key_exists($attribute, $this->filters)) ? $this->filters[$attribute] : [];
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === count($this->filters);
    }
}