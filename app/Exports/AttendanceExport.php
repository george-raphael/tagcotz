<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AttendanceExport implements FromQuery, WithHeadings, WithMapping
{
    public $searchQuery;
    public $status;

    public function __construct($searchQuery, $status)
    {
        $this->searchQuery = $searchQuery;
        $this->status = $status;
    }
    public function headings(): array
    {
        return [
            'First name',
            'Last name',
            'Phone number',
            'Email',
            'Institution',
            'Region',
            'District',
            'Payment Status',
        ];
    }

    public function query()
    {
        return Attendance::when(in_array($this->status, ['verified', 'unverified', 'invalid']), function ($query) {
            $query->where('status', $this->status);
        })
            ->where(function ($query) {
                $query->orWhere('first_name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('last_name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('institution', 'like', '%' . $this->searchQuery . '%')
                    ->orWhereHas('region', function ($query) {
                        $query->where('name', 'like', '%' . $this->searchQuery . '%');
                    })
                    ->orWhereHas('district', function ($query) {
                        $query->where('name', 'like', '%' . $this->searchQuery . '%');
                    });
            })
            ->with(['region', 'district'])
            ->latest();
    }

    public function map($attendance):array
    {
        return [
            'first_name' => $attendance->first_name,
            'last_name' => $attendance->last_name,
            'phone_number' => $attendance->phone_number,
            'email' => $attendance->email,
            'institution' => $attendance->institution,
            'region' => $attendance->region->name,
            'district' => $attendance->district->name,
            'status' => ucfirst($attendance->status)
        ];
    }
}
