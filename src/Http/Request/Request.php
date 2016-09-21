<?php

namespace CarterZenk\Slim\JsonApi\Http\Request;

use CarterZenk\Slim\JsonApi\Http\Request\Parameters\Fields;
use CarterZenk\Slim\JsonApi\Http\Request\Parameters\Filter;
use CarterZenk\Slim\JsonApi\Http\Request\Parameters\Included;
use CarterZenk\Slim\JsonApi\Http\Request\Parameters\Page;
use CarterZenk\Slim\JsonApi\Http\Request\Parameters\Sort;
use Slim\Http\Request as SlimRequest;

class Request extends SlimRequest
{
    /**
     * @return Included
     */
    public function getIncluded()
    {
        $includeParam = $this->getQueryParam('include', []);
        $included = new Included();

        if (is_string($includeParam) && strlen($includeParam)){
            $relationshipPaths = explode(',', $includeParam);
            foreach ($relationshipPaths as $relationshipPath) {
                $included->addIncluded($relationshipPath);
            }
        }

        return $included;
    }

    /**
     * @return Sort
     */
    public function getSort()
    {
        $sortParam = $this->getQueryParam('sort');
        $sort = new Sort();

        if (!empty($sortParam) && is_string($sortParam)) {
            $members = \explode(',', $sortParam);
            if (!empty($members)) {
                foreach ($members as $field) {
                    $key = ltrim($field, '-');
                    $sort->addField($key, ('-' === $field[0]) ? 'descending' : 'ascending');
                }
            }
        }

        return $sort;
    }

    /**
     * @return Page
     */
    public function getPage()
    {
        $pageParam = $this->getQueryParam('page');
        $page = new Page(
            (!empty($pageParam['number'])) ? $pageParam['number'] : 1,
            (!empty($pageParam['cursor'])) ? $pageParam['cursor'] : null,
            (!empty($pageParam['limit'])) ? $pageParam['limit'] : null,
            (!empty($pageParam['offset'])) ? $pageParam['offset'] : null,
            (!empty($pageParam['size'])) ? $pageParam['size'] : null
        );

        return $page;
    }

    /**
     * @return Filter
     */
    public function getFilters()
    {
        $filterParam = (array) $this->getQueryParam('filter', null);
        $filter = new Filter();

        foreach ($filterParam as $attribute => $value) {
            if(false === strstr($value, ',')) {
                $filter->addFilter($attribute, $value);
            } else {
                $values = explode(',', $value);
                foreach($values as $filterValue){
                    $filter->addFilter($attribute, $filterValue);
                }
            }
        }

        return $filter;
    }

    /**
     * @return Fields
     */
    public function getFields()
    {
        $fieldsParam = (array) $this->getQueryParam('fields', null);
        $fieldsParam = array_filter($fieldsParam);
        $fields = new Fields();

        foreach ($fieldsParam as $type => &$members) {
            $members = explode(',', $members);
            $members = array_map('trim', $members);

            foreach ($members as $member) {
                $fields->addField($type, $member);
            }
        }

        return $fields;
    }
}