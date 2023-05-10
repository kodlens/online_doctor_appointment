<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        //this will execute everyminute and look for data
        $schedule->call(function () {
            // DB::table('test')->insert([
            //     'testmsg' => 'schedule insert',
            // ]);

            
            $beforeDay = date('Y-m-d H:i', strtotime('+24 hour', strtotime(date('Y-m-d H:i')))); 

            $data = \DB::table('appointments as a')
                ->join('schedules as b', 'a.schedule_id', 'b.schedule_id')
                ->join('users as c', 'a.user_id', 'c.user_id')
                ->where('a.appointment_date', date('Y-m-d', strtotime($beforeDay)))
                ->where('b.time_from', date('H:i', strtotime($beforeDay)))
                ->where('a.is_notify', 0)
                ->get();

                //TIWASONUN
        
            foreach($data as $i){

                //$user = User::find($i->user_id);

                $msg = 'The '.$i->lname . ', ' . $i->fname . ', this is just a reminder that you have an appointment tomorrow at exactly '. date('h:i A', $i->time_from) .'. Your ref no. is: ' . $i->appointment_id . '.';
                
                try{ 
                    Http::withHeaders([
                        'Content-Type' => 'text/plain'
                    ])->post('http://'. env('IP_SMS_GATEWAY') .'/services/api/messaging?Message='.$msg.'&To='.$user->contact_no.'&Slot=1', []);
                }catch(Exception $e){} //just hide the error

                $appoint = Appointment::find($i->appointment_id);
                $appoint->is_notify = 1;
                $appoint->save();
            }

        })->everyMinute(); //loop everyminute

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
