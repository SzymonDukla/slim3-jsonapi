<?php

namespace CarterZenk\Slim\JsonApi\Http\Response;

use Slim\Http\Response;

class JsonApiResponse extends Response
{
    /**
     * JsonApiResponse constructor.
     * @param string $json
     * @param int $status
     * @param array $headers
     */
    public function __construct($json, $status = 200, $headers = [])
    {
        parent::__construct($status, null, null);

        foreach($headers as $headerKey => $headerValue){
            $this->headers->add($headerKey, $headerValue);
        }

        $this->headers->set('Content-Type', 'application/vnd.api+json');
        $this->write($json);
    }
}