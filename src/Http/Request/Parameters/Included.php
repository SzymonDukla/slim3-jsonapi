<?php

namespace CarterZenk\Slim\JsonApi\Http\Request\Parameters;

class Included
{
    /**
     * @var array
     */
    protected $included = [];

    /**
     * @param string $relationshipPath
     */
    public function addIncluded($relationshipPath)
    {
        $this->included[] = $relationshipPath;
    }

    /**
     * @return array
     */
    public function getIncluded()
    {
        return $this->included;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === count($this->included);
    }
}
