<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class InsufficientFundsException extends Exception
{
    public function render(Request $request)
    {
        return response()->json([
            'message' => 'Insufficient Balance',
            'data' => [],
        ], 500);
    }
}
