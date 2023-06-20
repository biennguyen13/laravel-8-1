<?php

namespace App\Http\Controllers;

use App\Events\TestEvent;
use App\Http\Requests\CreateUserRequest;
use App\Jobs\CreateUserJob;
use App\Jobs\SendEmailJob;
use App\Mail\WelcomeMail;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Queue;

class SendMailController extends Controller
{

    public function __construct()
    {
    }

    public function SendMail(Request $request)
    {
        // $name = $request->name;
        // $email = $request->email;

        // for ($i = 0; $i <= 10; $i++) {
        //     Mail::to($email)->later(now()->addMinutes(10), new WelcomeMail($name, $email));
        // }

        // $event = new TestEvent($request);
        // event($event);

        // return response()->json(['event' => $event]);

        // dispatch(new SendEmailJob());
        SendEmailJob::dispatch();

        return 'ok';
    }
}
