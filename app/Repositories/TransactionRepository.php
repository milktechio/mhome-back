<?php

namespace App\Repositories;

use App\Models\Transactions;
use App\Models\PurchaseComment;
use App\Models\CompanyCommission;
use App\Models\Variant;
use App\Traits\PaginateRepository;
use Auth;

class TransactionRepository
{
    use PaginateRepository;

    public function checkIfExists($hash, $index)
    {
        return $hash && $index;
    }

    public function checkIfExists2($hash, $index)
    {
        return \DB::connection('blockchain')
            ->table('transactions')
            ->where('payload', 'like', '%'.$hash.'%')
            ->orWhere('payload', 'like', '%'.$index.'%')
            ->count() ? true : false;
    }
}
