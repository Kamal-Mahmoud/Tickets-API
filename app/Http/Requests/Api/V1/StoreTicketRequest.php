<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;
use Illuminate\Foundation\Http\FormRequest;

class  StoreTicketRequest extends BaseTicketRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $authorIdAttribute = $this->routeIs('tickets.store') ? "data.relationships.author.data.id" : 'author';
        // لو الراوت ستور انا متوقعه جاي من الجيسون
        $rules = [
            "data.attributes.title" => "required|string",
            "data.attributes.description" => "required|string",
            "data.attributes.status" => "required|string|in:A,C,H,X ",
            $authorIdAttribute => "required|integer|exists:users,id",
        ];
        $user = $this->user();
        //if ($this->routeIs('tickets.store'))  // لان كل واحد هيعمل كرييت للتيكت بتاعته
        if ($user->tokenCan(Abilities::CreateOwnTicket)) {
            $rules[$authorIdAttribute] .= "|size:" . $user->id;
            // if the user has the ability to create a ticket , author ID need to match ID of logged user.
        }
        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->routeIs('authors.tickets.store')) {
            $this->merge([
                'author' => $this->route('author') // انا هنا بضيف رول جديدة و بقوله خلي قيمتها بالقيمة الللي في الراوت
            ]);
        }
    }


}
