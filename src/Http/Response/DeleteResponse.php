<?php

namespace CarterZenk\Slim\JsonApi\Http\Response;

class DeleteResponse extends JsonApiResponse
{
    /**
     * DeleteResponse constructor.
     * @param string $json
     * @param array $headers
     */
    public function __construct($json, $headers = [])
    {
        parent::__construct($json, 200, $headers);
    }
}