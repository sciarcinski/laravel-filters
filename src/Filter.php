<?php

namespace Sciarcinski\LaravelFilters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
    /** @var Request */
    protected $request;

    /** @var Builder */
    protected $query;
    
    protected $filters;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->filters = $request->get('filter');
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
     *
     * @return Builder
     */
    public function apply(Builder $query)
    {
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
                
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
        
        return $this->query;
    }
    
    /**
     * @param string $name
     *
     * @return string
     */
    protected function getFilterMethod($name)
    {
        return 'apply' . studly_case(str_replace('.', ' ', $name));
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
