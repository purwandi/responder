<?php

namespace Purwandi\Responder;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use League\Fractal\Manager as FractalManager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item as FractalItem;

class Manager implements Arrayable, Jsonable
{
    protected $resource;
    protected $manager;

    public function __construct()
    {
        $this->manager = new FractalManager;
    }

    /**
     * Transform the eloquent item
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  \League\Fractal\TransformerAbstract $transformer
     * @return \App\Support\Fractal\Manager
     */
    public function transformItem($model, $transformer = null)
    {
        $transformer    = $transformer ?: $model::transformer();
        $this->resource = new FractalItem($model, new $transformer);
        return $this;
    }

    /**
     * Transform the eloquent collection
     *
     * @param  \Illuminate\Support\Collection $collection
     * @param  \League\Fractal\TransformerAbstract $transformer
     * @return \App\Support\Fractal\Manager
     */
    public function transformCollection($collection, $transformer = null)
    {
        $model          = $this->resolveFromCollection($collection);
        $transformer    = $transformer ?: $model::transformer();
        $this->resource = new FractalCollection($collection, new $transformer);
        return $this;
    }

    /**
     * Transform the eloquent pagination
     *
     * @param  \Illuminate\Support\Collection $collection
     * @return \App\Support\Fractal\Manager
     */
    public function transformPaginator($collection)
    {
        $query = array_diff_key($_GET, array_flip(['page']));

        $this->transformCollection($collection->getCollection());

        foreach ($query as $key => $value) {
            $collection->addQuery($key, $value);
        }

        $this->resource->setPaginator(new IlluminatePaginatorAdapter($collection));
        return $this;
    }

    /**
     * Get class object from given collection
     *
     * @param  \Illuminate\Support\Collection $collection
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function resolveFromCollection($collection)
    {
        $class = $collection->first();

        // $collection->each(function ($model) use ($class) {
        //     if (get_class($model) !== get_class($class)) {
        //         throw new \InvalidArgumentException("Error Processing Request", 1);
        //     }
        // });

        return $class;
    }

    /**
     * Parsing includes
     *
     * @param  array  $includes
     * @return \App\Support\Fractal\Manager
     */
    public function includes(array $includes = [])
    {
        $this->manager->parseIncludes($includes);
        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $this->resource->setMetaValue('code', 200);
        $this->resource->setMetaValue('message', 'OK');
        return $this->manager->createData($this->resource)->toArray();
    }

    /**
     * Get the instance as an json.
     *
     * @return array
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }

}
