<?php

namespace App\Services;

use App\Models\Report;
use App\Repositories\ReportRepository;

class ReportService
{
    protected $ReportRepository;

    public function __construct(ReportRepository $ReportRepository)
    {
        $this->ReportRepository = $ReportRepository;
    }

    public function query($query){
        return $this->ReportRepository->query(Report::class, $query);
    }

    public function store($data)
    {
        return $this->ReportRepository->store($data);
    }

    public function update($data, $report)
    {
        return $this->ReportRepository->update($data, $report);
    }

    public function updateImage($data, $report)
    {
        return $this->ReportRepository->updateImage($data, $report);
    }

}
