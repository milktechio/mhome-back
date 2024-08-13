<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Http\Requests\TransactionController\StoreRequest;
use App\Http\Requests\TransactionController\StatusRequest;
use App\Http\Requests\TransactionController\UpdateRequest;
use App\Http\Requests\TransactionController\CommentRequest;
use App\Services\TransactionService;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        return $this->TransactionService->query($request->query());
    }

    public function indexAll(Request $request)
    {
        return $this->TransactionService->queryAll($request->query());
    }

}
