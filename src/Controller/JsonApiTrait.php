<?php

namespace CarterZenk\Slim\JsonApi\Controller;

use CarterZenk\Slim\JsonApi\Http\Request\Parameters\Fields;
use CarterZenk\Slim\JsonApi\Http\Request\Parameters\Filter;
use CarterZenk\Slim\JsonApi\Http\Request\Parameters\Included;
use CarterZenk\Slim\JsonApi\Http\Request\Parameters\Page;
use CarterZenk\Slim\JsonApi\Http\Request\Parameters\Sort;
use CarterZenk\Slim\JsonApi\Http\Request\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use NilPortugues\Api\JsonApi\Server\Errors\Error;
use NilPortugues\Api\JsonApi\Server\Errors\ErrorBag;
use Slim\Router;

trait JsonApiTrait
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var EncoderInterface
     */
    protected $encoder;

    /**
     * @param Router $router
     * @param EncoderInterface $encoder
     */
    public function __construct(Router $router, EncoderInterface $encoder)
    {
        $this->router  = $router;
        $this->encoder = $encoder;
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function getRoute(Request $request)
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
     * Returns the total number of results available for the current resource.
     *
     * @param Filter $filter
     * @param Page $page
     *
     * @return callable
     * @codeCoverageIgnore
     */
    protected function totalAmountResourceCallable(Filter $filter, Page $page)
    {
        return function () use ($filter, $page) {
            $idKey = $this->getDataModel()->getKeyName();

            // TODO: Implement this function to return the number of results possible with filter and page.

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
     * @param Fields $fields
     * @param Filter $filter
     * @param Included $include
     * @param Page $page
     * @param Sort $sort
     *
     * @return callable
     * @codeCoverageIgnore
     */
    protected function indexResourceCallable(Fields $fields, Filter $filter, Included $include, Page $page, Sort $sort)
    {
        return function () use ($fields, $filter, $include, $page, $sort) {

            // TODO: Implement the indexResourceCallable method.

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
     * @param int $id
     * @param string $relationship
     *
     * @return callable
     * @codeCoverageIgnore
     */
    protected function findRelationshipCallable($id, $relationship)
    {
        return function () use ($id, $relationship) {
            $idKey = $this->getDataModel()->getKeyName();
            $model = $this->getDataModelBuilder()->where($idKey, $id)->first();

            // TODO: Implement this function to first check if the relationship exists, and then return results.

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
        return function (array $data, ErrorBag $errorBag) {
            $model = $this->getDataModel()->newInstance();

            foreach ($data['attributes'] as $attribute => $value) {
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
     * @param int $id
     *
     * @return callable
     * @codeCoverageIgnore
     */
    protected function updateResourceCallable($id)
    {
        return function (array $data, ErrorBag $errorBag) use ($id) {
            $idKey = $this->getDataModel()->getKeyName();
            $model = $this->getDataModelBuilder()->where($idKey, $id)->first();

            try {
                $model->update($data['attributes']);
            } catch (\Exception $e) {
                $errorBag[] = new Error('update_failed', 'Could not update resource.');
                throw $e;
            }
        };
    }

    /**
     * @param int $id
     *
     * @return callable
     * @codeCoverageIgnore
     */
    protected function updateRelationshipCallable($id)
    {
        return function (array $data, ErrorBag $errorBag) use ($id) {
            $idKey = $this->getDataModel()->getKeyName();
            $model = $this->getDataModelBuilder()->where($idKey, $id)->first();

            // TODO: Set the new foreign key in the model.

            try {
                $model->update($data['attributes']);
            } catch (\Exception $e) {
                $errorBag[] = new Error('update_failed', 'Could not update relationship.');
                throw $e;
            }
        };
    }

    /**
     * @param $id
     *
     * @return callable
     * @codeCoverageIgnore
     */
    protected function deleteResourceCallable($id)
    {
        return function (ErrorBag $errorBag) use ($id) {
            $idKey = $this->getDataModel()->getKeyName();
            $model = $this->getDataModelBuilder()->where($idKey, $id)->first();

            try {
                return $model->delete();
            } catch (\Exception $e) {
                $errorBag[] = new Error('delete_failed', 'Could not delete resource.');
                throw $e;
            }
        };
    }
}
