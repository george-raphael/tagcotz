<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\District;
use App\Models\Event;
use App\Models\Region;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class MoveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move users from attendance to users table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $event = Event::create([
            'name' => '2023 Event',
            'status' => 0,
            'year' => '2023',
            'amount' => 300000,
            'event_date' => Carbon::now()->toDateString()
        ]);
        $attendancies  = Attendance::all();
        User::where('id',1)->update(['type'=>1]);
        User::destroy(User::where('id', '>', 1)->pluck('id')->toArray());
        foreach ($attendancies as $attendence) {

           $user =  User::create([
                'title' => $attendence['title'],
                'first_name' => $attendence['first_name'],
                'last_name' => $attendence['last_name'],
                'phone_number' => $attendence['phone_number'],
                'region_id' => $attendence['region_id'],
                'district_id' => $attendence['district_id'],
                'institution' => $attendence['institution'],
                'email' => $attendence['email'],
                'password' => bcrypt(strtolower($attendence['first_name'])),
            ]);

            $attendence->update([
                'paid_amount' => $event->amount,
                'event_id' => $event->id,
                'user_id' => $user->id,
            ]);
        }

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeignIdFor(Region::class);
            $table->dropForeignIdFor(District::class);
            $table->dropColumn([
                'first_name',
                'last_name',
                'phone_number',
                'email',
                'institution',
                'district_id',
                'region_id',
                'title',
            ]);
        });
        return Command::SUCCESS;
    }
}
