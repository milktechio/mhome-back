<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventController\CommentRequest;
use App\Http\Requests\EventController\StoreRequest;
use App\Models\Event;
use App\Models\EventComment;
use App\Services\{EventService};
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $EventService;

    public function __construct(EventService $EventService)
    {
        $this->EventService = $EventService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->EventService->query($request->query());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        return $this->EventService->store($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $event->like;
        $event->comments;

        return ok('', $event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        return $this->EventService->destroy($event);
    }

    public function like(Event $event)
    {
        return $this->EventService->like($event);
    }

    public function comment(CommentRequest $request, Event $event)
    {
        return $this->EventService->comment($request->validated(), $event);
    }

    public function deleteComment(EventComment $comment)
    {
        $comment->delete();

        return ok('comentario borrado correctamente');
    }
}
