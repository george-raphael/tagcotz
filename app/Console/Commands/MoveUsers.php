<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Console\Command;

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
        $users  = Attendance::all();
        foreach ($users as $user) {
            $this->info($user->id);
            User::create([
                'title' => $user['title'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'phone_number' => $user['phone_number'],
                'region_id' => $user['region_id'],
                'district_id' => $user['district_id'],
                'institution' => $user['institution'],
                'email' => $user['email'],
                'password' => bcrypt(strtolower($user['first_name'])),
            ]);
        }
        return Command::SUCCESS;
    }
}
