<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteController\StoreRequest;
use App\Http\Requests\VoteController\UpdateRequest;
use App\Http\Requests\VoteController\VotingRequest;
use App\Models\Vote;
use App\Services\VoteService;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    protected $VoteService;

    public function __construct(VoteService $VoteService)
    {
        $this->VoteService = $VoteService;
    }

    public function index(Request $request)
    {
        return $this->VoteService->query($request->query());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        return $this->VoteService->store($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Vote $vote)
    {
        $vote->myVote = $vote->myVote;

        return ok('', $vote);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Vote $vote)
    {
        return $this->VoteService->update($request->validated(), $vote);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vote $vote)
    {
        $vote->unlink('image_url');
        $vote->delete();

        return ok('Votacion eliminada correctamente');
    }

    public function voting(VotingRequest $request)
    {
        return $this->VoteService->voting($request->validated());
    }

    public function result(Vote $vote)
    {
        return $this->VoteService->result($vote);
    }
}
