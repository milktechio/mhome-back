<?php

namespace App\Services;

use App\Models\Transactions;
use App\Repositories\TransactionRepository;
use Auth;

class TransactionService
{
    protected $TransactionRepository;

    public function __construct(TransactionRepository $TransactionRepository)
    {
        $this->TransactionRepository = $TransactionRepository;
    }

    public function query($query)
    {
        return $this->TransactionRepository->query(Transaction::class, $query, function ($query) {
            $query->where('user_id', Auth::user()->id);

            return $query;
        }, function ($data) {
            $types = TransactionType::all()->groupby('id');

            foreach ($data as $key => $transaction) {
                $data[$key]->type = $types[$transaction->type_id][0]->name ?? 'Transaccion';
            }

            return $data;
        });
    }

    public function queryAll($query)
    {
        return $this->TransactionRepository->query(Transaction::class, $query, null, function ($data) {
            $types = TransactionType::all()->groupby('id');

            foreach ($data as $key => $transaction) {
                $data[$key]->type = $types[$transaction->type_id][0]->name ?? 'Transaccion';
            }

            return $data;
        });
    }

    public function checkIfExists($hash, $index)
    {
        return $this->TransactionRepository->checkIfExists($hash, $index);
    }
}
