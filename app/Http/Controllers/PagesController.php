<?php

namespace App\Http\Controllers;

use App\Enums\EventStatus;
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
use App\Models\Event;
use App\Services\Payment;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class PagesController extends Controller
{
    public function dashboard()
    {

        if (request()->filled('orderId')) {
            (new Payment())->checkOrderStatus(request('orderId'));
            return redirect()->back();
        }

        if (auth()->user()->type == 1) {
            $data['res']['events'] = Event::latest()->limit(10)->get();
        } else {
            $event = Event::where('status', 1)->first();
            if ($event) {
                $event['attendance'] = $event?->attendance();
            }
            $data['res']['event'] = $event;
        }

        return inertia('Dashboard', $data);
    }

    public function export()
    {
        $status = request('status');
        $searchQuery = request('searchQuery');

        return Excel::download(new AttendanceExport($searchQuery, $status), ucfirst($status) . ' ' . now()->format('Y-m-d') . '.xlsx');
    }

    public function attendances(Event $event)
    {
        $status = request('status');

        $attendees = $event->attendencies()->when(in_array($status, ['verified', 'unverified', 'invalid']), function ($query) use ($status) {
            $query->where('status', $status);
        })
            ->with(['user:id,first_name,last_name,phone_number,email,institution,title,region_id,district_id', 'user.region', 'user.district', 'paymentAttempts:id,attendance_id,status,transaction_status_number,payment_phone_number'])
            ->latest();
        $data['res']['attendeesData'] = $attendees->paginate(20)
            ->withQueryString();
        // dd( $data['res']['attendeesData']);
        $data['res']['status'] = $status;
        $data['res']['totalCount'] = $attendees->count();

        $data['res']['eventProp'] = $event;

        return inertia('Attendance', $data);
    }

    public function searchAttendees()
    {
        $status = request('status');
        $searchQuery = request('searchQuery');
        $event_id = request('event_id');

        $data = $this->filterAttendances($event_id, $searchQuery, $status);
        return $data;
    }
    public function filterAttendances($event_id, $searchQuery, $status)
    {
        return Attendance::where('event_id', $event_id)
            // ->whereHas('paymentAttempts', function ($query) use ($searchQuery) {
            //     $query->where('payment_phone_number', 'like', '%' . $searchQuery . '%');
            // })
            // ->toSql();
            ->when(in_array($status, ['verified', 'unverified', 'invalid']), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->where(function ($query) use ($searchQuery) {
                $query->orWhereHas('user', function ($query) use ($searchQuery) {
                        $query->where('users.first_name', 'like', '%' . $searchQuery . '%');
                        $query->orWhere('users.last_name', 'like', '%' . $searchQuery . '%');
                        $query->orWhere('users.institution', 'like', '%' . $searchQuery . '%');
                    })
                    ->orWhereHas('paymentAttempts', function ($query) use ($searchQuery) {
                        $query->where('payment_attempts.payment_phone_number', 'like', '%' . $searchQuery . '%');
                    });
            })
            ->with(['user.region', 'user.district', 'paymentAttempts'])
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
        $append = ['payment_method' => null];
        if ($data['status'] == 'verified') {
            $append = ['payment_method' => 'MANUAL'];
        }
        $attendance->update($data + $append);
        return redirect()->back();
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
    public function getRegions()
    {
        return Region::orderBy('name')->get();
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
        $event = Event::where('status', EventStatus::ACTIVE->getValue())->latest()->first();
        $attendance = array_map(function ($value) {
            return "TAGCOTZ-" . $value;
        }, [
            ...$event->attendencies()->where('status', 'verified')->pluck('id')
        ]);
        return response()->json([
            'success' => true,
            'data' => $attendance,
            'count' => count($attendance)
        ]);
    }
    //IDS
    public function printIds(Event $event)
    {
        $data['attendees'] = $event->attendencies()
        ->when(request()->filled('attendance_id'), function($query){
            $query->where('id',request('attendance_id'));
        })
        ->with('user.region', 'user.district')
        ->where('id_printed',false)
        // ->where('status', 'verified')->orderBy('id', 'asc')
        ->get();

        $pdf = PDF::loadView('pdf.pdf-ids', $data);
        
        Attendance::whereIn('id', $data['attendees']->pluck('id'))
        ->update(['id_printed'=>true]);

        return $pdf->stream('TAGCOTZ-ids.pdf');
    }
}
