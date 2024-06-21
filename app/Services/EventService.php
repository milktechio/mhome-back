<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\EventRepository;

class EventService
{
    protected $EventRepository;

    public function __construct(EventRepository $EventRepository)
    {
        $this->EventRepository = $EventRepository;
    }

    public function query($query)
    {
        return $this->EventRepository->query(Event::class, $query, function ($query) {
            return $query->with('like');
        });
    }

    public function all()
    {
        return $this->EventRepository->all();
    }

    public function store($data)
    {
        return $this->EventRepository->store($data);
    }

    public function destroy($event)
    {
        return $this->EventRepository->destroy($event);
    }

    public function like($event)
    {
        return $this->EventRepository->like($event);
    }

    public function comment($data, $event)
    {
        return $this->EventRepository->comment($data, $event);
    }
}
