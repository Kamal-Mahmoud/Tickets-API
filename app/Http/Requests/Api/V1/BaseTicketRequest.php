<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseTicketRequest extends FormRequest
{
    public function mappedAttributes(array $otherAttributes =[])
    {
        $attributeMap =  array_merge([
            // taking what's provided from request as key && value : name in the model
            "data.attributes.title" => "title",
            "data.attributes.description" => "description",
            "data.attributes.status" => "status",
            "data.attributes.createdAt" => "created_at",
            "data.attributes.updatedAt" => "updated_at",
            "data.relationships.author.data.id" => "user_id",
        ], $otherAttributes);

        $attributesToUpdate = [];
        foreach ($attributeMap as $key => $attribute) {
            if ($this->has($key)) { // if request has input key
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }
        return $attributesToUpdate;
    }

    public function messages()
    {
        return [
            "data.attributes.status" => "The Status value is Invalid . please use A, C, H or X",
        ];
    }
}
