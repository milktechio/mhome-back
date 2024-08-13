<?php

namespace App\Repositories;

use App\Models\Vote;
use App\Models\Voting;
use App\Models\User;
use App\Traits\PaginateRepository;
use Auth;

class VoteRepository
{
    use PaginateRepository;

    public function __construct()
    {
    }

    public function store($data)
    {
        $user = Auth::user();
        $users = User::all();

        $options = array_filter(explode('|', $data['options']));
        if ($data['minimum_participations'] <= count($users)) {
            $vote = Vote::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'options' => $options,
                'minimum_participations' => $data['minimum_participations'],
                'status' => $data['status'],
                'date_end' => $data['date_end'],
            ]);
            $vote->storeImage($data['image'], 'image_url');

            return ok('Votacion creada correctamente', $vote);
        } else {
            return bad_request('El minimo de participantes es mayor a los usuarios registrados');
        }
    }

    public function update($data, $vote)
    {
        $vote->update($data);

        return ok('Votacion actualizada correctamente', $vote);
    }

    public function voting($data)
    {
        $user = Auth::user();
        $vote = Vote::find($data['vote_id']);
        $error = false;

        $voting = Voting::where('user_id', $user->id)->where('vote_id', $data['vote_id'])->first() ?? false;

        if ($voting) {
            $error = forbidden('Ya has votado');
        }

        if (! in_array($data['option'], $vote->options)) {
            $error = bad_request('No existe esa opcion');
        }

        if ($error) {
            return $error;
        }

        $voting = Voting::create([
            'user_id' => $user['id'],
            'vote_id' => $data['vote_id'],
            'option' => $data['option'],
        ]);

        $porcentajes = $this->result($vote)->getData();

        $results = [
            'voting' => $voting,
            'porcentajes' => $porcentajes->data,
        ];

        return ok('Votacion guardada correctamente', $results);
    }

    public function result($vote)
    {
        $voting = Voting::where('vote_id', $vote->id)->get();

        $total = count($voting);

        $options = [];

        foreach ($vote->options as $option) {
            $result = [];
            $result['option'] = $option;
            $result['votes'] = count($voting->where('option', $option));
            $result['percentaje'] = $total ? ($result['votes'] * 100 / $total) : '0';
            $options[] = $result;
        }

        return ok('Resultados', $options);
    }
}
