<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Rating;
use App\Models\DocRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AverageOverallRatingsExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $month = $this->filters['month'] ?? now()->format('Y-m');
        $date = Carbon::createFromFormat('Y-m', $month);

        return Rating::selectRaw(
            'YEAR(ratings.created_at) as year,
             MONTH(ratings.created_at) as month,
             users.name as user_name,
             doc_requests.document_type as document_type,
             AVG(ratings.overall) as avg_overall'
        )
        ->leftJoin('doc_requests', 'ratings.doc_request_id', '=', 'doc_requests.id')
        ->leftJoin('users', 'ratings.user_id', '=', 'users.id')
        ->whereYear('ratings.created_at', $date->year)
        ->whereMonth('ratings.created_at', $date->month)
        ->groupBy('year', 'month', 'users.name', 'doc_requests.document_type')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get([
            'year',
            'month',
            'user_name',
            'document_type',
            'avg_overall'
        ]);
    }

    public function headings(): array
    {
        return [
            'Year',
            'Month',
            'User Name',
            'Document Type',
            'Average Overall Rating',
        ];
    }
}
