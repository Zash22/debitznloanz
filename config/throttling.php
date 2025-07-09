<?php
return [
    'maxAttempts' => env('RATE_LIMIT_MAX', 60),
    'enabled' => env('RATE_LIMIT_ON', true),
];
