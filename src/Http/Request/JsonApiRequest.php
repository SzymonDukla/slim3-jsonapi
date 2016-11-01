<?php

namespace CarterZenk\Slim\JsonApi\Http\Request;

use CarterZenk\Slim\JsonApi\Http\Request\Parameters\Sort;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\SortParameterInterface;
use Slim\Http\Request as SlimRequest;

class JsonApiRequest extends SlimRequest implements EncodingParametersInterface {

    /**
     * Get requested include paths.
     *
     * @return array|null
     */
    public function getIncludePaths()
    {
        $includeParam = $this->getQueryParam('include', []);
        if (empty($includeParam)) {
            return null;
        }

        $included = [];

        if (is_string($includeParam) && strlen($includeParam)) {
            $relationshipPaths = explode(',', $includeParam);
            foreach ($relationshipPaths as $relationshipPath) {
                $included[] = $relationshipPath;
            }
        }

        return $included;
    }

    /**
     * Get filed names that should be in result.
     *
     * @return array|null
     */
    public function getFieldSets()
    {
        // TODO: Implement getFieldSets() method.
    }

    /**
     * Get filed names that should be in result.
     *
     * @param string $type
     *
     * @return string[]|null
     */
    public function getFieldSet($type)
    {
        // TODO: Implement getFieldSet() method.
    }

    /**
     * Get sort parameters.
     *
     * @return SortParameterInterface[]|null
     */
    public function getSortParameters()
    {
        $sortParam = $this->getQueryParam('sort');
        $sort = [];

        if (!empty($sortParam) && is_string($sortParam)) {
            $members = \explode(',', $sortParam);
            if (!empty($members)) {
                foreach ($members as $field) {
                    $sort[] = new Sort($field);
                }
            }
        }

        return $sort;
    }

    /**
     * Get pagination parameters.
     *
     * Pagination parameters are not detailed in the specification however a keyword 'page' is reserved for pagination.
     * This method returns key and value pairs from input 'page' parameter.
     *
     * @return array|null
     */
    public function getPaginationParameters()
    {
        // TODO: Implement getPaginationParameters() method.
    }

    /**
     * Get filtering parameters.
     *
     * Filtering parameters are not detailed in the specification however a keyword 'filter' is reserved for filtering.
     * This method returns key and value pairs from input 'filter' parameter.
     *
     * @return array|null
     */
    public function getFilteringParameters()
    {
        // TODO: Implement getFilteringParameters() method.
    }

    /**
     * Get top level parameters that have not been recognized by parser.
     *
     * @return array|null
     */
    public function getUnrecognizedParameters()
    {
        // TODO: Implement getUnrecognizedParameters() method.
    }

    /**
     * Returns true if inclusion, field set, sorting, paging, and filtering parameters are empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        // TODO: Implement isEmpty() method.
    }
}