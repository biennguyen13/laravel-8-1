<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Jobs\CreateUserJob;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Queue;

class UploadController extends Controller
{

    public function __construct()
    {
    }

    public function upload(Request $request)
    {
        // Log::info('upload', [$request->all()]);
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Lấy thông tin về tên file
            $fileName = $file->getClientOriginalName();

            // Lấy thông tin về kích thước file (tính bằng byte)
            $fileSize = $file->getSize();

            // Lấy thông tin về loại MIME của file
            $fileMimeType = $file->getMimeType();

            // Lấy đường dẫn tạm thời của file trên server
            $temporaryPath = $file->getRealPath();

            // Thực hiện xử lý tệp tin tại đây

            // Lưu file vào thư mục "storage/app/uploads"
            $path = $file->store('uploads');

            return response()->json([
                'fileName' => $fileName,
                'fileSize' => $fileSize,
                'fileMimeType' => $fileMimeType,
                'path' =>  $path,
            ]);
        }
        return 'please upload file';
    }
}
