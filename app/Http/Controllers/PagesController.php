<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\District;
use Inertia\Inertia;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;


class PagesController extends Controller
{
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
    public function storeUsajiri()
    {
        $data = request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required|unique:attendances',
            'region_id' => 'required',
            'district_id' => 'required',
            'email' => 'required|unique:attendances',
            'institution' => 'required',
            'title' => 'required'
        ]);

        Attendance::create($data);

        return redirect()->route('successful');
    }
    public function getDistrictsByRegionId($region)
    {
        return District::where('region_id', $region)->orderBy('name')->get();
    }
    public function usajiri()
    {
        $data['regions'] = Region::orderBy('name')->get();
        $data['canLogin'] = Route::has('login');
        $data['canRegister'] = Route::has('register');
        $data['laravelVersion'] = Application::VERSION;
        $data['phpVersion'] = PHP_VERSION;
        return Inertia::render('Usajiri/Usajiri', $data);
    }

    public function successful()
    {
        return Inertia::render('Usajiri/Successful');
    }
}
