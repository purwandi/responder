<?php

namespace Purwandi\Responder;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;

class Responder
{
    protected $manager;
    protected $includes = [];
    protected $code     = 200;

    protected $transforms = [
        Transformable::class => 'transformItem',
        Collection::class    => 'transformCollection',
        Paginator::class     => 'transformPaginator',
    ];

    public function __construct()
    {
        $this->manager = new Manager;
    }

    /**
     * Print out success response
     *
     * @param  string|object $message
     * @param  array $data
     * @return \Illuminate\Http\Response
     */
    public function success($message, $data = [])
    {
        foreach ($this->transforms as $class => $transform) {
            if ($message instanceof $class) {

                // Call fractal manager
                $manager = call_user_func([$this->manager, $transform], $message);

                if (count($this->includes) > 0) {
                    $manager->includes($this->includes);
                }

                // Merge data
                $data = array_merge($manager->toArray(), $data);

                return $this->response($data, 200);
            }
        }

        return $this->message($message, $data);
    }

    /**
     * Parsing include transfomer
     *
     * @return void
     */
    public function with()
    {
        if (func_num_args() > 0) {
            $this->includes = func_get_args();
        }
        return $this;
    }

    /**
     * Print out error message
     *
     * @param  string  $message
     * @param  integer $code
     * @return \Illuminate\Http\Response
     */
    public function error($message = 'ERROR', $code = 500)
    {
        return $this->message($message, [], $code);
    }

    /**
     * Print out unauthorized error message
     *
     * @param  string  $message
     * @param  integer $code
     * @return \Illuminate\Http\Response
     */
    public function unauthorized($message = 'UNAUTHORIZED', $code = 401)
    {
        return $this->message($message, [], $code);
    }

    /**
     * Print out forbidden message
     *
     * @param  string $message
     * @return \Illuminate\Http\Response
     */
    public function forbidden($message = 'FORBIDDEN')
    {
        return $this->message($message, [], 403);
    }

    /**
     * Print out not found message
     *
     * @param  string $message
     * @return \Illuminate\Http\Response
     */
    public function notfound($message = 'NOT_FOUND')
    {
        return $this->message($message, [], 404);
    }

    /**
     * Return Http response
     *
     * @param  string  $message
     * @param  integer $code
     * @return \Illuminate\Http\Response
     */
    private function response($content, $code)
    {
        return response()->json($content, $code);
    }

    /**
     * Build http message
     *
     * @param  string  $message
     * @param  array   $data
     * @param  integer $code
     * @see    response()
     */
    private function message($message, $data = [], $code = 200)
    {
        $content = [
            'data' => $data,
            'meta' => [
                'code'    => $code,
                'message' => $message,
            ],
        ];

        return $this->response($content, $code);
    }

}
