<?php

namespace CarterZenk\Slim\JsonApi\Http\Request\Parameters;

class Fields
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @param string $type
     * @param string $fieldName
     */
    public function addField($type, $fieldName)
    {
        $this->fields[(string) $type][] = (string) $fieldName;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return string[]
     */
    public function getTypes()
    {
        return array_keys($this->fields);
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function getMembers($type)
    {
        return (array_key_exists($type, $this->fields)) ? $this->fields[$type] : [];
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === count($this->fields);
    }
}
