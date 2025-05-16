<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\TicketPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use MongoDB\Driver\Exception\AuthenticationException;

class TicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

    public function index(TicketFilter $filters)
    {
//        if ($this->include("author")) {
//            return TicketResource::collection(Ticket::with('user')->paginate());
//        }
//        return TicketResource::collection(Ticket::paginate());

        return TicketResource::collection(Ticket::filter($filters)->paginate()); // CALL filter method in our model and pass the filters
        // filters : contain all query string parameters stuff  , status or title
        // those filters coming from ticket filter object receiving as argument


    }

    public function store(StoreTicketRequest $request)
    {
        //Check if provide user exist or Not
        try {
//            $user = User::findOrFail($request->input("data.relationships.author.data.id"));// check user in StoreRequest Rules
            $this->isAble('store', Ticket::class);
            return new TicketResource(Ticket::create($request->mappedAttributes()));

        } catch (AuthorizationException $exception) {
            return $this->error("You are not allowed to create tickets.", 401);
        }

    }

    public function show($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            if ($this->include("author")) {
                return new TicketResource($ticket->load('user'));
            }
            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error("Ticket not found", 404);
        }
    }

    public function update(UpdateTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $this->isAble('update', $ticket);
            //$ticket = [$ticket , TicketPolicy] $$ update : name of the method i created in Policy
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket Not Found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update that resource', 401);
        }
    }

    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $this->isAble('replace', $ticket);
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket Not Found', 404);
        }
    }


    function destroy($ticket)
    {
        try {
            $ticket = Ticket::findOrFail($ticket);
            $this->isAble('delete', $ticket);
            $ticket->delete();
            return $this->ok("Ticket deleted successfully");
        } catch (ModelNotFoundException $exception) {
            return $this->error("Ticket not found", 404);
        }

    }
}
