<?php

namespace App\Http\Controllers;

use Image;
use Inertia\Inertia;
use App\Models\Region;
use App\Models\District;
use App\Models\Attendance;
use App\Exports\AttendanceExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\UsajiriRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class PagesController extends Controller
{
    public function dashboard()
    {
        $data['verified'] = Attendance::where('status', 'verified')
            ->count();
        $data['unverified'] = Attendance::where('status', 'unverified')
            ->count();
        $data['invalid'] = Attendance::where('status', 'invalid')
            ->count();
        $data['all'] = Attendance::count();

        return inertia('Dashboard', $data);
    }

    public function export()
    {
        $status = request('status');
        $searchQuery = request('searchQuery');

        return Excel::download(new AttendanceExport($searchQuery, $status),ucfirst($status).' '.now()->format('Y-m-d'). '.xlsx');
    }

    public function attendances()
    {
        $status = request('status');

        $data['attendees'] = Attendance::when(in_array($status, ['verified', 'unverified', 'invalid']), function ($query) use ($status) {
            $query->where('status', $status);
        })
            ->with(['region', 'district', 'user:id,name'])
            ->latest()
            ->paginate(20);
        $data['status'] = $status;

        return inertia('Attendance', $data);
    }

    public function searchAttendees()
    {
        $status = request('status');
        $searchQuery = request('searchQuery');

        $data = $this->filterAttendances($searchQuery, $status);
        return $data;
    }
    public function filterAttendances($searchQuery, $status)
    {
        return Attendance::when(in_array($status, ['verified', 'unverified', 'invalid']), function ($query) use ($status) {
            $query->where('status', $status);
        })
            ->where(function ($query) use ($searchQuery) {
                $query->orWhere('first_name', 'like', '%' . $searchQuery . '%')
                    ->orWhere('last_name', 'like', '%' . $searchQuery . '%')
                    ->orWhere('institution', 'like', '%' . $searchQuery . '%')
                    ->orWhereHas('region', function ($query) use ($searchQuery) {
                        $query->where('name', 'like', '%' . $searchQuery . '%');
                    })
                    ->orWhereHas('district', function ($query) use ($searchQuery) {
                        $query->where('name', 'like', '%' . $searchQuery . '%');
                    });
            })
            ->with(['region', 'district'])
            ->latest()
            ->paginate(20);
    }
    public function storeUsajili(UsajiriRequest $request)
    {
        DB::transaction(function () use ($request) {
            $attendance = Attendance::create($request->validated());
            $attendance->addMediaFromRequest('receipt_file')->toMediaCollection('receipts');
        });
        return redirect()->route('successful');
    }
    public function updateUsajili(Attendance $attendance)
    {
        $data = request()->validate([
            'status' => 'required|string',
        ]);
        $attendance->update($data + ['user_id' => auth()->id()]);
        return redirect()->route('dashboard');
    }
    public function deleteUsajili(Attendance $attendance)
    {
        $attendance->delete();
        return back();
    }

    public function getDistrictsByRegionId($region)
    {
        return District::where('region_id', $region)->orderBy('name')->get();
    }
    public function usajili()
    {
        $data['regions'] = Region::orderBy('name')->get();
        $data['canLogin'] = Route::has('login');
        $data['canRegister'] = Route::has('register');
        $data['laravelVersion'] = Application::VERSION;
        $data['phpVersion'] = PHP_VERSION;
        return Inertia::render('Usajili/Usajili', $data);
    }

    public function successful()
    {
        return Inertia::render('Usajili/Successful');
    }

    public function getVerifiedIdAPI()
    {

        $attendance = array_map(function ($value) {
            return "TAGCOTZ-" . $value;
        }, [...Attendance::where('status', 'verified')->pluck('id')]);
        return response()->json([
            'success' => true,
            'data' => $attendance,
            'count' => count($attendance)
        ]);
    }
    //IDS
    public function printIds()
    {
        $data['attendees'] = Attendance::query()->where('status', 'verified')->orderBy('id', 'asc')->get()->chunk(2);
        $pdf = PDF::loadView('pdf.pdf-ids', $data);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream('TAGCOTZ-ids.pdf');
    }
}
