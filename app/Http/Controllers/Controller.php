<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($message, $data = [])
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], 200);
    }

    public function notify($data)
    {
        if ($data['status'] == 200) {
            return $this->success($data['message'], $data['data']);
        } else {
            return $this->error($data['message'], $data['data'] ?? []);
        }
    }

    public function error($message, $data = [])
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], 500);
    }

    public function valiationErrors($errors)
    {
        return response()->json([
            'errors' => $errors->errors(),
            'message' => 'Validation errors',
        ], 422);
    }

    public function buildValidationError($data)
    {
        return response()->json([
            'errors' => $data,
            'message' => 'Validation errors',
        ], 422);
    }

    public function messageError($err)
    {
        if (env('APP_ENV') == 'local' || env('APP_ENV') == 'staging') {
            return $err->getMessage() . ' - ' . $err->getLine() . ' - ' . $err->getFile();
        } else {
            return __('global.process_failed');
        }
    }
}
