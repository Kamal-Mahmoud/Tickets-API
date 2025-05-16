<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected $builder;
    protected $request;
    protected $sortable;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function apply(Builder $builder)
    {
        $this->builder = $builder;
        foreach ($this->request->all() as $key => $value) {
            if (method_exists($this, $key)) {   // method exist in this object
                $this->$key($value); // call method and pass value
            }
        }
        return $builder;
    }

    protected function filter($arr)
    {
        foreach ($arr as $key => $value) {    // عامل دي علشان احط حاجات الفلتر في قوسين الاراري filter[status]
            if (method_exists($this, $key)) {
                $this->$key($value); // call method and pass value
            }
        }
        return $this->builder;
    }

    protected function sort($value)
    {
        $sortAttributes = explode(',', $value);

        foreach ($sortAttributes as $sortAttribute) {

            $direction = 'asc';

            if (strpos($sortAttribute, '-') === 0) {
                $direction = 'desc';
                $sortAttribute = substr($sortAttribute, 1);
            }
            if (!in_array($sortAttribute, $this->sortable) && !array_key_exists($sortAttribute, $this->sortable)) {
                // لو برتب بكلمة مش عندي اتجاهلها و اكمل عادي
                continue;  //
            }
            $columnName = $this->sortable[$sortAttribute] ?? null;

            if ($columnName == null) {
                $columnName = $sortAttribute;
            }

            return $this->builder->orderBy($columnName, $direction);
        }

    }

}
