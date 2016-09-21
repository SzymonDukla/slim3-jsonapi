<?php

namespace CarterZenk\Slim\JsonApi\Http\Request\Parameters;

/**
 * Class Page.
 */
class Page
{
    /**
     * @var string|int|null
     */
    protected $number;
    /**
     * @var string|int|null
     */
    protected $size;
    /**
     * @var string|int|null
     */
    protected $cursor;
    /**
     * @var string|int|null
     */
    protected $limit;
    /**
     * @var string|int|null
     */
    protected $offset;

    /**
     * @param $number
     * @param $cursor
     * @param $limit
     * @param $offset
     * @param $size
     */
    public function __construct($number = 1, $size = 10, $cursor, $limit, $offset)
    {
        $this->number = $number;
        $this->size = $size;
        $this->cursor = $cursor;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return int|string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return int|string
     */
    public function getCursor()
    {
        return $this->cursor;
    }

    /**
     * @return int|string
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return int|string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return int|string
     */
    public function getOffset()
    {
        return $this->offset;
    }
}
