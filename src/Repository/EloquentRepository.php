<?php

namespace SzymonDukla\Slim3\JsonApi\Repository;

use Illuminate\Database\Eloquent\Model;
use NilPortugues\Foundation\Infrastructure\Model\Repository\Eloquent\EloquentRepository as Repository;

/**
 * Class Repository.
 */
class EloquentRepository extends Repository
{
    /**
     * @var string
     */
    protected $modelClass;

    /**
     * EloquentRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        parent::__construct();
        $this->modelClass = get_class($model);
    }

    /**
     * {@inheritdoc}
     */
    protected function modelClassName()
    {
        return $this->modelClass;
    }
}
