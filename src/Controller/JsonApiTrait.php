<?php

namespace CarterZenk\Slim3\JsonApi\Controller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use NilPortugues\Api\JsonApi\Http\Request\Request;
use NilPortugues\Api\JsonApi\Server\Errors\Error;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use CarterZenk\Slim3\JsonApi\Eloquent\EloquentHelper;
use CarterZenk\Slim3\JsonApi\JsonApiSerializer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Router;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Slim\Http\Response as SlimResponse;

/**
 * Class JsonApiTrait
 * @package CarterZenk\Slim3\JsonApi\Controller
 */
trait JsonApiTrait
{
    /**
     * @var JsonApiSerializer
     */
    protected $serializer;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var EloquentHelper
     */
    protected $eloquentHelper;

    /**
     * @var int
     */
    protected $pageSize = 10;

    /**
     * @param JsonApiSerializer $serializer
     * @param Router $router
     * @param EloquentHelper $eloquentHelper
     */
    public function __construct(JsonApiSerializer $serializer, Router $router, EloquentHelper $eloquentHelper)
    {
        $this->serializer = $serializer;
        $this->router = $router;
        $this->eloquentHelper = $eloquentHelper;
    }

    /**
     * @param SymfonyResponse $response
     * @return SlimResponse
     */
    protected function getSlimResponse(SymfonyResponse $response)
    {
        $newResponse = new SlimResponse();
        $newResponse->write($response->getContent());
        $newResponse = $newResponse->withStatus($response->getStatusCode());
        foreach($response->headers->all() as $key => $value){
            $newResponse = $newResponse->withHeader($key, $value);
        }
        return $newResponse;
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    protected function getRoute(ServerRequestInterface $request)
    {
        $route = $request->getUri()->getScheme().'://';
        $route .= $request->getUri()->getHost();

        if(!empty($request->getUri()->getPort())){
            $route .= ':'.$request->getUri()->getPort();
        }

        $route .= $request->getUri()->getPath();

        return $route;
    }

    /**
     * @param SlimResponse $response
     * @return SlimResponse
     */
    protected function addHeaders(SlimResponse $response){
        return $response;
    }

    /**
     * Returns the total number of results available for the current resource.
     *
     * @return callable
     * @codeCoverageIgnore
     */
    protected function totalAmountResourceCallable()
    {
        return function () {
            $idKey = $this->getDataModel()->getKeyName();

            return $this->getDataModelBuilder()->count([$idKey]);
        };
    }

    /**
     * Returns an Eloquent Model.
     *
     * @return Model
     */
    abstract public function getDataModel();

    /**
     * Returns an Eloquent Query Builder.
     *
     * @return Builder
     */
    abstract public function getDataModelBuilder();


    /**
     * Returns a list of resources based on pagination criteria.
     *
     * @param Request $request
     * @return callable
     * @codeCoverageIgnore
     */
    protected function listResourceCallable(Request $request)
    {
        return function () use ($request) {
            return $this->eloquentHelper->paginate($request, $this->serializer, $this->getDataModelBuilder(), $this->pageSize)->get();
        };
    }

    /**
     * @param $id
     *
     * @return callable
     * @codeCoverageIgnore
     */
    protected function findResourceCallable($id)
    {
        return function () use ($id) {
            $idKey = $this->getDataModel()->getKeyName();
            $model = $this->getDataModelBuilder()->where($idKey, $id)->first();

            return $model;
        };
    }

    /**
     * Reads the input and creates and saves a new Eloquent Model.
     *
     * @return callable
     * @codeCoverageIgnore
     */
    protected function createResourceCallable()
    {
        return function (array $data, array $values, ErrorBag $errorBag) {
            $model = $this->getDataModel()->newInstance();

            foreach ($values as $attribute => $value) {
                $model->setAttribute($attribute, $value);
            }

            if (!empty($data['id'])) {
                $model->setAttribute($model->getKeyName(), $data['id']);
            }

            try {
                $model->save();

                //We need to load the model from the DB in case the user is utilizing getRequiredFields() on the transformer.
                $model = $model->fresh();
            } catch (\Exception $e) {
                $errorBag[] = new Error('creation_error', 'Resource could not be created');
                throw $e;
            }

            return $model;
        };
    }

    /**
     * @return callable
     * @codeCoverageIgnore
     */
    protected function updateResourceCallable()
    {
        return function (Model $model, array $data, array $values, ErrorBag $errorBag) {
            foreach ($values as $attribute => $value) {
                $model->$attribute = $value;
            }
            try {
                $model->update();
            } catch (\Exception $e) {
                $errorBag[] = new Error('update_failed', 'Could not update resource.');
                throw $e;
            }
        };
    }

    /**
     * @param $id
     *
     * @return \Closure
     */
    protected function deleteResourceCallable($id)
    {
        return function () use ($id) {
            $idKey = $this->getDataModel()->getKeyName();
            $model = $this->getDataModel()->query()->where($idKey, $id)->first();

            return $model->delete();
        };
    }
}
