<?php

namespace App\Listeners;

use App\Events\TestEvent;
use App\Mail\WelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TestListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TestEvent  $event
     * @return void
     */
    public function handle(TestEvent $event)
    {
        $name = $event->data->name;
        $email = $event->data->email;

        for ($i = 0; $i <= 10; $i++) {
            Mail::to($email)->later(now()->addMinutes(10), new WelcomeMail($name, $email));
        }

        Log::info('handling kkk', [response()->json($event)]);
    }
}
