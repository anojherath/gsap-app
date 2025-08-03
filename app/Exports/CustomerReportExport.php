<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class CustomerReportExport implements FromCollection
{
    public function collection()
    {
        return DB::table('harvest as h')
            ->leftJoin('paddy as p', 'h.paddy_id', '=', 'p.id')
            ->leftJoin('seed_orders as so', 'p.id', '=', 'so.paddy_id')
            ->leftJoin('users as u', 'so.user_id', '=', 'u.id')
            ->leftJoin('fertiliser_order as fo', 'u.id', '=', 'fo.user_id')
            ->select(
                'p.type as harvest_type',
                'h.creation_date as harvest_date',
                'u.address as origin',
                'so.creation_date as seed_provider_date',
                'fo.type as fertilizer_type',
                'fo.creation_date as fertilizer_applied_date'
            )
            ->get();
    }
}
