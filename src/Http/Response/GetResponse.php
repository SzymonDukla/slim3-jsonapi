<?php

namespace CarterZenk\Slim\JsonApi\Http\Response;

class GetResponse extends JsonApiResponse
{
    /**
     * GetResponse constructor.
     * @param string $json
     * @param array $headers
     */
    public function __construct($json, $headers = [])
    {
        parent::__construct($json, 200, $headers);
    }
}