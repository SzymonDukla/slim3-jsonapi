<?php

namespace CarterZenk\Slim\JsonApi\Http\Request\Parameters;

use Neomerx\JsonApi\Contracts\Encoder\Parameters\SortParameterInterface;

class Sort implements SortParameterInterface
{
    private $field;
    private $descending;

    /**
     * Sort constructor.
     * @param $sortParameter
     */
    public function __construct($sortParameter)
    {
        $this->field = ltrim($sortParameter, '-');
        $this->descending = ('-' === $sortParameter[0]) ? true : false;
    }

    /**
     * Get sort field name.
     *
     * @return string
     */
    public function getField() {
        return $this->field;
    }

    /**
     * Get true if parameter is ascending.
     *
     * @return bool
     */
    public function isAscending() {
        return !$this->descending;
    }

    /**
     * Get true if parameter is descending.
     *
     * @return bool
     */
    public function isDescending() {
        return $this->descending;
    }
}
