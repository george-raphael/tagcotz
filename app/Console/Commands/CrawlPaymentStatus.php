<?php

namespace App\Console\Commands;

use Alphaolomi\Swahilies\Swahilies;
use App\Models\Attendance;
use Illuminate\Console\Command;

class CrawlPaymentStatus extends Command
{
    const API_KEY = "YmQ2YmJhNmZmMDNmNDFlOGE0MzkxMmJkOWExN2ZiYjA=";
    protected $swahilies;
    public function __construct()
    {
        parent::__construct();
        $this->swahilies = Swahilies::create([
            'apiKey' => self::API_KEY,
            'username' => 'TAGCO',
            'isLive' => true, // ie. sandbox mode
        ]);
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:payment-attempts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl Payment Attempts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        $attendances = Attendance::where([
            'event_id' => 2,
            'crawled' => 0
        ])->get();

        foreach ($attendances as $key => $attendance) {
            sleep(2);
            $this->info($key);
            $response = $this->swahilies->payments()->find($attendance->order_number);

            if (array_key_exists('order', $response) && count($response['order'])) {

                $hasPaid = collect($response['order'])->where('status', 'paid')->first();

                if ($hasPaid) {
                    if ($attendance->status != 'verified') {
                        $attendance->update([
                            'status' => 'verified',
                            'paid_amount' => $hasPaid['amount']
                        ]);
                    }
                }

                foreach ($response['order'] as $order) {
                    $attendance->paymentAttempts()->create([
                        'payment_phone_number' => $order['phone_number'],
                        'status' => $order['status'],
                        'transaction_status_number' => $order['order_id'],
                    ]);
                }
                $attendance->update(['crawled' => 1]);
            }
        }
        $this->info("Done!");


        return Command::SUCCESS;
    }
}
