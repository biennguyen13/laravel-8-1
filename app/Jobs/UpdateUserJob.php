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

class UpdateUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $id;
    protected $data;
    protected $userRepository;

    public function __construct($id, $data)
    {
        $this->id = $id;
        $this->data = $data;
        $this->data['password'] = Hash::make($this->data['password']);
        $this->userRepository = new UserRepository();
    }

    public function handle()
    {

        Log::info(sprintf('%s: %s Start', __CLASS__, __FUNCTION__), [
            'id' => $this->id,
            'data' =>  $this->data
        ]);

        try {
            DB::beginTransaction();

            $result = $this->userRepository->updateUser($this->id, $this->data);

            DB::commit();

            return [
                'success' => $result,
                'data' => $result,
                'code' => 200,
                'message' => $result  ? 'Success' : 'Not found',
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
