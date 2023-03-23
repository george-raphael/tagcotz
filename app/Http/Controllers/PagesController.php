<?php

namespace App\Http\Controllers;

use Image;
use Inertia\Inertia;
use App\Models\Region;
use App\Models\District;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Http\Requests\UsajiriRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class PagesController extends Controller
{
    public function dashboard()
    {
        $data['attendees'] = Attendance::with(['region', 'district', 'user:id,name'])->latest()
            ->paginate(20);
        return inertia('Dashboard', $data);
    }

    public function searchAttendees()
    {
        $searchQuery = request('searchQuery');
        $data = Attendance::with(['region', 'district'])
            ->where('first_name', 'like', '%' . $searchQuery . '%')
            ->orWhere('last_name', 'like', '%' . $searchQuery . '%')
            ->latest()
            ->paginate(20);
        return $data;
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
