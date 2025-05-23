<?php

namespace App\Http\Filters\V1;

class TicketFilter extends QueryFilter
{
    protected $sortable = [
        'title',
        'status',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',

    ];

    public function status($value)
    {
        return $this->builder->whereIn('status', explode(',', $value));
        // builder : access query builder
    }

    public function include($value)
    {
        return $this->builder->with($value); // "user" :   name of method in Ticket Model
    }

    public function title($value)
    {
        $likeStr = str_replace('*', '%', $value);
        return $this->builder->where('title', 'like', '%' . $likeStr . '%');
    }

    public function createdAt($value)
    {
        $dates = explode(",", $value);
        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }
        return $this->builder->whereDate('created_at', $value);
    }

    public function updatedAt($value)
    {
        $dates = explode(",", $value);
        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }
        return $this->builder->whereDate('updated_at', $value);
    }
}
