<?php

use Log;

if (!function_exists('save_date')) {
    function logMessage($endpoint, $input, $exception)
    {
        Log::info("Endpoint: = " . $endpoint);
        Log::info($input);
        Log::info("Exception: = " . $exception);
    }
}
