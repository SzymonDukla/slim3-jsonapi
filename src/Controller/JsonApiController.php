<?php

namespace CarterZenk\Slim3\JsonApi\Controller;

use Carbon\Carbon;
use NilPortugues\Api\JsonApi\Http\Request\Request;
use NilPortugues\Api\JsonApi\Server\Actions\CreateResource;
use NilPortugues\Api\JsonApi\Server\Actions\DeleteResource;
use NilPortugues\Api\JsonApi\Server\Actions\GetResource;
use NilPortugues\Api\JsonApi\Server\Actions\ListResource;
use NilPortugues\Api\JsonApi\Server\Actions\PatchResource;
use NilPortugues\Api\JsonApi\Server\Actions\PutResource;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class JsonApiController
{
    use JsonApiTrait;

    /**
     * Get many resources.
     *
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function indexAction(ServerRequestInterface $request)
    {
        $apiRequest = new Request($request);

        $page = $apiRequest->getPage();
        if (!$page->size()) {
            $page->setSize($this->pageSize);
        }

        $fields = $apiRequest->getFields();
        $sorting = $apiRequest->getSort();
        $included = $apiRequest->getIncludedRelationships();
        $filters = $apiRequest->getFilters();

        $resource = new ListResource($this->serializer, $page, $fields, $sorting, $included, $filters);

        $totalAmount = $this->totalAmountResourceCallable();
        $results = $this->listResourceCallable($apiRequest);

        $controllerAction = '\\'.get_called_class().'@index';
        $uri = $this->uriGenerator($controllerAction);

        return $this->addHeaders($resource->get($totalAmount, $results, $uri, get_class($this->getDataModel())));
    }

    /**
     * Get single resource.
     *
     * @param ServerRequestInterface $request
     * @param $id
     * @return Response
     */
    public function showAction(ServerRequestInterface $request, $id)
    {
        $apiRequest = new Request($request);

        $resource = new GetResource(
            $this->serializer,
            $apiRequest->getFields(),
            $apiRequest->getIncludedRelationships()
        );

        $find = $this->findResourceCallable($id);

        return $this->addHeaders($resource->get($id, get_class($this->getDataModel()), $find));
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function createAction(ServerRequestInterface $request)
    {
        $createResource = $this->createResourceCallable();
        $resource = new CreateResource($this->serializer);

        $model = $this->getDataModel();
        $data = (array) $request->getParsedBody()['data'];
        if (array_key_exists('attributes', $data) && $model->timestamps) {
            $data['attributes'][$model::CREATED_AT] = Carbon::now()->toDateTimeString();
            $data['attributes'][$model::UPDATED_AT] = Carbon::now()->toDateTimeString();
        }

        return $this->addHeaders(
            $resource->get($data, get_class($this->getDataModel()), $createResource)
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param $id
     * @return Response
     */
    protected function putAction(ServerRequestInterface $request, $id)
    {
        $find = $this->findResourceCallable($id);
        $update = $this->updateResourceCallable();

        $resource = new PutResource($this->serializer);
        $model = $this->getDataModel();
        $data = (array) $request->get('data');
        if (array_key_exists('attributes', $data) && $model->timestamps) {
            $data['attributes'][$model::UPDATED_AT] = Carbon::now()->toDateTimeString();
        }

        return $this->addHeaders(
            $resource->get($id, $data, get_class($model), $find, $update)
        );
    }

    /**
     * @param ServerRequestInterface $request
     * @param $id
     * @return Response
     */
    protected function patchAction(ServerRequestInterface $request, $id)
    {
        $find = $this->findResourceCallable($id);
        $update = $this->updateResourceCallable();

        $resource = new PatchResource($this->serializer);

        $model = $this->getDataModel();
        $data = (array) $request->ge('data');
        if (array_key_exists('attributes', $data) && $model->timestamps) {
            $data['attributes'][$model::UPDATED_AT] = Carbon::now()->toDateTimeString();
        }

        return $this->addHeaders(
            $resource->get($id, $data, get_class($model), $find, $update)
        );
    }

    /**
     * @param $id
     *
     * @return Response
     */
    public function delete($id)
    {
        $find = $this->findResourceCallable($id);

        $delete = $this->deleteResourceCallable($id);

        $resource = new DeleteResource($this->serializer);

        return $this->addHeaders($resource->get($id, get_class($this->getDataModel()), $find, $delete));
    }

}
