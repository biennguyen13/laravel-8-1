<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class CreateUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $name;
    protected $email;
    protected $password;
    protected $jobResult;
    protected $userRepository;

    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->jobResult = null;
        $this->userRepository = new UserRepository();
    }

    public function getJobResult()
    {
        return $this->jobResult;
    }

    public function handle()
    {

        Log::info(sprintf('%s: %s Start', __CLASS__, __FUNCTION__), [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ]);

        // sleep(3);

        try {
            DB::beginTransaction();

            $user = $this->userRepository->createUser($this->name, $this->email, Hash::make($this->password));

            DB::commit();

            return [
                'success' => true,
                'data' => $user,
                'code' => 200,
                'message' => 'Success',
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error(sprintf('%s: %s Failed: %s', __CLASS__, __FUNCTION__, $exception->getMessage()), [
                'exception' => $exception->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'data' => null,
                'code' => $exception->getCode(),
                'message' => 'Failed: ' . $exception->getMessage(),
            ];
        }
    }
}
