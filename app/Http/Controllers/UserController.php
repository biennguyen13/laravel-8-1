<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Jobs\CreateUserJob;
use App\Jobs\DeleteUserJob;
use App\Jobs\UpdateUserJob;
use App\Models\User;
use App\Repositories\UserRepository;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Queue;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;



class UserController extends Controller
{
    private $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users|max:255',
        'password' => 'required|string|min:8',
    ];
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request, $id)
    {
        $user =  $this->userRepository->getUser($id);
        return response()->json([
            'success' =>  !!$user,
            'data' =>   $user,
            'code' => 200,
            'message' =>  $user ? 'Success' : 'Not found',
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'success' => false,
                'data' =>  $errors,
                'code' => 200,
                'message' => 'Something went wrong',
            ]);
        }

        $data = $request->all();
        $job = new UpdateUserJob($id, $data);
        $result =  $job->handle();
        return response()->json($result);
    }

    public function delete(Request $request, $id)
    {
        // Gọi phương thức xóa người dùng từ repository
        // dd($request, $id);
        // $result =  $this->userRepository->deleteUser($id);
        $job = new DeleteUserJob($id);
        $result =  $job->handle();
        return response()->json($result);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'success' => false,
                'data' =>  $errors,
                'code' => 200,
                'message' => 'Something went wrong',
            ]);
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        $job = new CreateUserJob($name, $email, $password);
        $result =  $job->handle();
        // $this->dispatchSync($job);

        // return response()->json($job->getJobResult());
        return response()->json($result);
    }

    public function createMultiUser(Request $request)
    {

        // Log::info('upload', [$request->all()]);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Đường dẫn lưu trữ tạm thời file
            $filePath = $file->getRealPath();

            // Đọc file Excel bằng PhpSpreadsheet
            $spreadsheet = IOFactory::load($filePath);

            // Lấy ra sheet đầu tiên
            $sheet = $spreadsheet->getActiveSheet();

            // Lấy dữ liệu từ sheet
            $array = $sheet->toArray();
            $result = array();

            foreach ($array as $row) {
                $filteredRow = array_filter($row, 'strlen');
                $result[] = array_values($filteredRow);
            }

            $array = $result;

            array_shift($array);

            foreach ($array as $row) {
                [$name, $email, $password]  = $row;
                $user = ['name' => $name, 'email' => $email, 'password' => $password];
                $validator = Validator::make($user,  $this->rules);

                if ($validator->fails()) {
                    $errors = $validator->errors();
                    return response()->json([
                        'success' => false,
                        'data' =>  $errors,
                        'code' => 200,
                        'message' => 'Something went wrong at ' . $email,
                    ]);
                }

                $job = new CreateUserJob($name, $email, $password);
                // $this->dispatch($job);
                $job->handle();
            }

            // Trả về kết quả
            return response()->json(['data' => $array]);
        }
        return 'please upload file';
    }

    public function user_all()
    {
        $user = User::all();
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $user
        ]);
    }
}
