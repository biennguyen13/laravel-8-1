<?php

namespace App\Http\Controllers;

use App\Events\TestEvent;
use App\Http\Requests\CreateUserRequest;
use App\Jobs\CreateUserJob;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Queue;

class TestEventController extends Controller
{

    public function __construct()
    {
    }

    public function testEvent(Request $request)
    {
        $event = new TestEvent($request);
        event($event);
        return response()->json(['event' => $event]);
    }
}
