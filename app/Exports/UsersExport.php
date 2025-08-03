<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::with('userType')->get()->map(function ($user) {
            return [
                'Name'        => $user->first_name . ' ' . $user->last_name,
                'NIC'         => $user->nic,
                'Mobile'      => $user->mobile_number,
                'Address'     => $user->address,
                'Type'        => $user->userType->user_type ?? '',
                'Company'     => $user->company_name ?? '-',
                'Reg Number'  => $user->reg_number ?? '-',
                'Created At'  => $user->creation_date,
            ];
        });
    }

    public function headings(): array
    {
        return ['Name', 'NIC', 'Mobile', 'Address', 'Type', 'Company', 'Reg Number', 'Created At'];
    }
}
