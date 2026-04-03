<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;

class Handler extends Exception
{
    //
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'message' => 'User not autenticated'
        ], 404);
    }
}
