<?php

namespace App\Http\Controllers;

use Alphaolomi\Swahilies\Swahilies;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\PaymentAttempt;
use Exception;
use Illuminate\Http\Request;

class EventController extends Controller
{
    const API_KEY = "YmQ2YmJhNmZmMDNmNDFlOGE0MzkxMmJkOWExN2ZiYjA=";
    protected $swahilies;

    public function __construct()
    {
        $this->swahilies = Swahilies::create([
            'apiKey' => self::API_KEY,
            'username' => 'TAGCO',
            'isLive' => true, // ie. sandbox mode
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewEvent(Event $event)
    {
        $data['res']['eventProp'] = $event;
        $data['res']['verified'] = $event->attendencies()->where('status', 'verified')
            ->count();
        $data['res']['unverified'] = $event->attendencies()->where('status', 'unverified')
            ->count();
        $data['res']['invalid'] = $event->attendencies()->where('status', 'invalid')
            ->count();
        $data['res']['all'] = $event->attendencies()->count();

        return inertia('ManageEvent', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'status' => 'required|integer',
            'year' => 'required|string',
            'amount' => 'required|integer',
            'event_date' => 'required|string|date'
        ]);
        Event::create($fields);
        return redirect()->back()->with('success', 'Event created!');
    }

    public function update(Request $request, Event $event)
    {

        $fields = $request->validate([
            'name' => 'required|string',
            'status' => 'required|integer',
            'year' => 'required|string',
            'amount' => 'required|integer',
            'event_date' => 'required|string|date'
        ]);
        $event->update($fields);
        return redirect()->back()->with('success', 'Event updated!');
    }

    public function jisajili(Event $event)
    {
        Attendance::firstOrCreate([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'order_number' => str(explode('-', str()->uuid())[4])->upper(),
        ]);
        return redirect()->back()->with('success', 'Umefanikiwa kujisajili, Fanya malipo!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->back()->with('success', 'Event removed!');
    }



    public function lipia(Attendance $attendance)
    {
        request()->validate([
            'phone_number' => 'required|string|min:10|max:15'
        ]);

        $formattedNumber = '255' . substr(trim(request('phone_number')), -9);

        try {
            $response = $this->swahilies->payments()->directRequest([
                // TZS by default
                'amount' => $attendance->event->amount,
                // 255 is country code for Tanzania, Only Tanzania is supported for now
                'orderId' => $attendance->order_number,
                'phoneNumber' => $formattedNumber,
                'cancelUrl' => "https://tagcotz.com/api/cancel",
                'webhookUrl' => "https://tagcotz.com/api/response",
                'successUrl' => "https://tagcotz.com/api/success",
                'metadata' => [],
            ]);

            $attendance->update([
                'merchant_order_number' => $response['transaction_id'],
                'payment_phone_number' => $formattedNumber,
            ]);

            $attendance->paymentAttempts()->create([
                'merchant_order_number' => $response['transaction_id'],
                'payment_phone_number' => $formattedNumber,
            ]);

            return back()->with('success', 'Ombi la malipo limetumwa tafadhali kamilisha muamala kwenye simu yako.');
        } catch (Exception $e) {
            return back()->with('success', 'Kuna shida ya mtandao jaribu tena!');
        }
        $response = $this->swahilies->payments()->directRequest([
            // TZS by default
            'amount' => $attendance->event->amount,
            // 255 is country code for Tanzania, Only Tanzania is supported for now
            'orderId' => $attendance->order_number,
            'phoneNumber' => $formattedNumber,
            'cancelUrl' => "https://tagcotz.com/api/cancel",
            'webhookUrl' => "https://tagcotz.com/api/response",
            'successUrl' => "https://tagcotz.com/api/success",
            'metadata' => [],
        ]);

        return back()->with('success', 'Ombi la malipo limetumwa tafadhali kamilisha muamala kwenye simu yako.');
    }

    public function successfulPayment(Request $request)
    {

        try {
            info('Hit in Successful Payment Hook');
            info($request['transaction_details']);
            $transactionDetails = $request['transaction_details'];
            $attendance = Attendance::where('order_number', $transactionDetails['order_id'])->first();
            $attendance->status = 'verified';
            $attendance->receipt = $transactionDetails['reference_id'];
            $attendance->save();
        } catch (Exception $e) {
            info($e->getMessage());
        }
    }

    public function cancelPayment(Request $request)
    {
        try {
            $transactionDetails = $request['transaction_details'];
            $attendance = Attendance::where('order_number', $transactionDetails['order_id'])->first();
            $attendance->status = 'unverified';
            $attendance->save();
        } catch (Exception $e) {
            info($e->getMessage());
        }
    }
}
