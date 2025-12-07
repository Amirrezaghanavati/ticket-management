<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FakeApiController extends Controller
{
    public function __invoke()
    {
        $success = (bool) random_int(0, 1);
        if ($success) {
            return response()->json(['message' => 'OK'], 200);
        }
        return response()->json(['message' => 'Failed'], 500);
    }
}
