<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;

class AuthorsController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(User::select('users.*')
            ->join('tickets', 'users.id', '=', 'tickets.user_id')
            ->filter($filters)
            ->distinct()
            ->paginate());
    }

    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $author)
    {
        if ($this->include("tickets")) {
            return new UserResource($author->load("tickets"));
        }
        return new UserResource($author);
    }

    public function update(UpdateUserRequest $request, User $user)
    {

    }

    public function destroy(User $user)
    {
        //
    }
}
