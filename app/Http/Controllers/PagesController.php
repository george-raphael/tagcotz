<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\District;
use Inertia\Inertia;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class PagesController extends Controller
{
    public function dashboard()
    {
        $data['attendees'] = Attendance::with(['region', 'district'])->latest()
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
    public function storeUsajili()
    {
        $data = request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required|unique:attendances',
            'region_id' => 'required',
            'district_id' => 'required',
            'email' => 'required|unique:attendances',
            'institution' => 'required',
            'title' => 'required',
            'receipt_file' => 'required|mimes:jpeg,jpg,png'
        ]);


        $attendance = Attendance::create($data);
        if (request()->hasFile('receipt_file')) {
            $attendance->clearMediaCollection('receipts');
            $attendance->addMediaFromRequest('receipt_file')->toMediaCollection('receipts');
        }
        return redirect()->route('successful');
    }
    public function updateUsajili(Attendance $attendance)
    {
        $data = request()->validate([
            'status' => 'required|string',
        ]);
        $attendance->update($data);
        return redirect()->route('dashboard');
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
            'data' =>$attendance,
            'count'=>count($attendance)
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
