<?php

namespace App\Repositories;

use App\Traits\PaginateRepository;
use Auth;
use App\Models\Report;

class ReportRepository
{
    use PaginateRepository;

    public function store($data)
    {
        $data['user_id'] = Auth::user()->id;

        $report = Report::create($data);
        $report->storeImage($data['image'], 'image_url');

        return ok('Reporte creado correctamente', $report);
    }

    public function update($data, $report)
    {
        foreach ($data as $key => $value) {
            $report->$key = $value;
        }

        $report->save();

        return ok('Reporte actualizado correctamente', $report);
    }

    public function updateImage($data, $report)
    {
        $report->unlink('image_url');
        $report->storeImage($data['image'], 'image_url');

        return ok('Imagen cambiada correctamente', $report);

    }
}
