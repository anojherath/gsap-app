<?php

namespace App\Exports;

use App\Models\Harvest;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HarvestOrdersExport implements FromCollection, WithHeadings
{
    protected $farmerId;

    public function __construct($farmerId)
    {
        $this->farmerId = $farmerId;
    }

    public function collection()
    {
        return Harvest::where('user_id', $this->farmerId)
            ->select('id', 'qty', 'creation_date', 'status')
            ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Quantity', 'Date', 'Status'];
    }
}
