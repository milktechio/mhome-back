<?php

namespace App\Services;

use App\Models\Vote;
use App\Repositories\VoteRepository;

class VoteService
{
    protected $VoteRepository;

    public function __construct(VoteRepository $VoteRepository)
    {
        $this->VoteRepository = $VoteRepository;
    }

    public function query($query)
    {
        return $this->VoteRepository->query(Vote::class, $query);
    }

    public function store($data)
    {
        return $this->VoteRepository->store($data);
    }

    public function update($data, $vote)
    {
        return $this->VoteRepository->update($data, $vote);
    }

    public function voting($data)
    {
        return $this->VoteRepository->voting($data);
    }

    public function result($vote)
    {
        return $this->VoteRepository->result($vote);
    }
}
