<?php

namespace Sciarcinski\LaravelFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class Filter
{
    /** @var Request */
    protected $request;

    /** @var Builder */
    protected $query;

    protected $filters;

    protected $only = ['*'];

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * @param Builder $query
     * @param $input
     * @param array $only
     *
     * @return Builder
     */
    public function apply(Builder $query, $input = 'filter', array $only = ['*'])
    {
        $this->only = $only;
        $this->getFilters($input);
        $this->applyFilters($query);

        return $this;
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    protected function applyFilters(Builder $query)
    {
        $this->query = $query;

        if (is_array($this->filters)) {
            foreach ($this->filters as $filter => $value) {
                $method = $this->getFilterMethod($filter);

                if ($this->canUseFilter($filter) && method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }

        return $this->query;
    }

    /**
     * @param string $input
     */
    protected function getFilters($input)
    {
        $this->filters = is_null($input) ?
            $this->request()->all() :
            $this->request()->get($input);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getFilterMethod($name)
    {
        return 'apply' . Str::studly(str_replace('.', ' ', $name));
    }

    /**
     * @param string $filter
     *
     * @return bool
     */
    protected function canUseFilter($filter)
    {
        if ('*' === Arr::first($this->only)) {
            return true;
        }

        return in_array($filter, $this->only);
    }

    /**
     * @param $method
     * @param $args
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->query, $method], $args);
    }
}
