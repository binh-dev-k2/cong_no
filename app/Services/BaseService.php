<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class BaseService
{
    protected function handleException(\Throwable $th)
    {
        Log::error("Error in " . $th->getFile() . " on line " . $th->getLine() . ": " . $th->getMessage());
        throw $th;
    }
}
