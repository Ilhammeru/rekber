<?php

namespace App\Exceptions;

use Exception;

class AddDeductBalanceException extends Exception
{
    public function render($message)
    {
        return response()->json([
            'message' => env('APP_ENV') == 'local' ? $message : 'Failed to process transaction',
            'data' => [],
        ], 500);
    }
}
