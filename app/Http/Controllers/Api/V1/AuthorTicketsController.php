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
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AuthorTicketsController extends ApiController
{
    protected $policyClass = Ticket::class;


    public function index($author, TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::where('user_id', $author)
            ->filter($filters)
            ->paginate());
    }

    public function store(StoreTicketRequest $request, $author_id)
    {
        try {
            $this->isAble('store', Ticket::class);
            return new TicketResource(Ticket::create($request->mappedAttributes([
                'author' => 'user_id',

            ])));

        } catch (AuthorizationException $exception) {
            return $this->error("You are not allowed to create tickets.", 401);
        }
    }

    public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            $this->isAble("replace", $ticket);
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket Not Found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error("You are not allowed to Replace tickets.", 401);
        }
    }


    public function update(UpdateTicketRequest $request, $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            $this->isAble("update", $ticket);
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket Not Found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error("You are not allowed to Replace tickets.", 401);
        }
    }


    public function destroy($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            $this->isAble("delete", $ticket);
            $ticket->delete();
            return $this->ok("Ticket Deleted successfully");

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket Not Found', 404);
        }
    }
}
