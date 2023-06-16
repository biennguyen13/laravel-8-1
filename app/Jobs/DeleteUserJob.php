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

class DeleteUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $id;
    protected $userRepository;

    public function __construct($id)
    {
        $this->id = $id;
        $this->userRepository = new UserRepository();
    }

    public function handle()
    {

        Log::info(sprintf('%s: %s Start', __CLASS__, __FUNCTION__), [
            'id' => $this->id,
        ]);

        try {
            DB::beginTransaction();

            $result = $this->userRepository->deleteUser($this->id);

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
