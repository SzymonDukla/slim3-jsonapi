<?php

namespace CarterZenk\Slim\JsonApi\Http\Response;

class PostResponse extends JsonApiResponse
{
    /**
     * PostResponse constructor.
     * @param string $json
     * @param array $headers
     */
    public function __construct($json, $headers = [])
    {
        parent::__construct($json, 201, $headers);
    }
}