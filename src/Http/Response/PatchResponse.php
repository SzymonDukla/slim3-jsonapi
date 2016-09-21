<?php

namespace CarterZenk\Slim\JsonApi\Http\Response;

class PatchResponse extends JsonApiResponse
{
    /**
     * PatchResponse constructor.
     * @param string $json
     * @param array $headers
     */
    public function __construct($json, $headers = [])
    {
        parent::__construct($json, 202, $headers);
    }
}