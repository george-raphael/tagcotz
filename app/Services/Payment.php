<?php

namespace App\Services;

use Alphaolomi\Swahilies\Swahilies;
use App\Models\Attendance;

class Payment
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
    public function checkOrderStatus(string $orderId)
    {
        $attendance = Attendance::where('order_number', $orderId)->first();

        if ($attendance && $attendance->status == 'verified') {
            return [
                'status' => 'verified'
            ];
        }

        $response = $this->swahilies->payments()->find($orderId);
        info($response);
        if (count($response['order'])) {
            $hasPaid = collect($response['order'])->where('status','paid')->first();
            if ($hasPaid) {
                $attendance = Attendance::where('order_number', $orderId)->first();
                if ($attendance->status != 'verified') {
                    $attendance->update([
                        'status' => 'verified',
                        'paid_amount' => $hasPaid['amount']
                    ]);
                }
                return [
                    'status' => 'verified'
                ];
            }
        }
        return ['status' => 'unverified'];
    }
}
