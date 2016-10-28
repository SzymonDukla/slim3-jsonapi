<?php

namespace CarterZenk\Slim\JsonApi\Controller;

use CarterZenk\Slim\JsonApi\Http\Request\Request;
use CarterZenk\Slim\JsonApi\Http\Response\DeleteResponse;
use CarterZenk\Slim\JsonApi\Http\Response\GetResponse;
use CarterZenk\Slim\JsonApi\Http\Response\PatchResponse;
use CarterZenk\Slim\JsonApi\Http\Response\PostResponse;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use Slim\Http\Response;

abstract class JsonApiController
{
    use JsonApiTrait;

    /**
     * Get many resources.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function indexResourceAction(Request $request, Response $response, array $args)
    {
        $fields = $request->getFields();
        $filter = $request->getFilters();
        $include = $request->getIncluded();
        $page = $request->getPage();
        $sort = $request->getSort();

        $index = $this->indexResourceCallable($fields, $filter, $include, $page, $sort);
        $totalAmount = $this->totalAmountResourceCallable($filter, $page);

        $results = $index();

        // TODO: get the response data using the neomerx serializer and the indexResourceCallable function.


        return new GetResponse($results, $response->getHeaders());
    }

    /**
     * Get single resource.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function findResourceAction(Request $request, Response $response, array $args)
    {
        $fields = $request->getFields();
        $included = $request->getIncluded();

        $find = $this->findResourceCallable($args['id'], $fields, $included);

        // TODO: get the response data using the neomerx serializer and the findResourceCallable function.
        $results = $find();

        return new GetResponse($results, $response->getHeaders());
    }

    /**
     * Get a relationship on a resource.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function findRelationshipAction(Request $request, Response $response, array $args)
    {
        $findRelationship = $this->findRelationshipCallable($args['id'], $args['relationship']);

        // TODO: Implement findRelationshipAction

        $results = $findRelationship();

        return new GetResponse($results, $response->getHeaders());
    }

    /**
     * Get a related resource.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function findRelatedResourceAction(Request $request, Response $response, array $args)
    {
        // TODO: Implement findRelatedResourceAction

        return $response;
    }

    /**
     * Create a resource.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function createResourceAction(Request $request, Response $response, array $args)
    {
        $data = (array) $request->getParsedBody()['data'];
        $create = $this->createResourceCallable();

        // TODO: Implement the create functionality.
        $results = $create($data);

        return new PostResponse($results, $response->getHeaders());
    }

    /**
     * Update a resource.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    protected function updateResourceAction(Request $request, Response $response, array $args)
    {
        $update = $this->updateResourceCallable($args['id']);

        $data = (array) $request->getParsedBody()['data'];

        $results = $update($data, new ErrorBag());

        return new PatchResponse($results, $response->getHeaders());
    }

    /**
     * Update a relationship.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    protected function updateRelationshipAction(Request $request, Response $response, array $args)
    {
        // TODO: Implement updateRelationshipAction
        $update = $this->updateRelationshipCallable($args['id']);

        $data = (array) $request->getParsedBody()['data'];

        $results = $update($data, new ErrorBag());

        return new PatchResponse($results, $response->getHeaders());
    }

    /**
     * Delete a resource.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function deleteResourceAction(Request $request, Response $response, array $args)
    {
        $delete = $this->deleteResourceCallable($args['id']);

        $results = $delete(new ErrorBag());

        return new DeleteResponse($results, $response->getHeaders());
    }

}
