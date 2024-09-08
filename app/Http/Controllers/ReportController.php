<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Http\Requests\ReportController\StoreRequest;
use App\Http\Requests\ReportController\UpdateRequest;
use App\Http\Requests\ReportController\UpdateStatusRequest;
use App\Http\Requests\ReportController\UpdateImageRequest;

class ReportController extends Controller
{
    protected $ReportService;

    public function __construct(ReportService $ReportService)
    {
        $this->ReportService = $ReportService;
    }

    public function index(Request $request)
    {
        return $this->ReportService->query($request->query());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return $this->ReportService->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        return ok('', $report);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Report $report)
    {
        return $this->ReportService->update($request->validated(), $report);
    }

    public function updateImage(UpdateImageRequest $request, Report $report)
    {
        return $this->ReportService->updateImage($request->validated(), $report);
    }

    public function updateStatus(UpdateStatusRequest $request, Report $report)
    {
        return $this->ReportService->update($request->validated(), $report);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        $report->delete();

        return ok('Reporte eliminado correctamente');
    }
}
