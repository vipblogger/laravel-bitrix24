<?php

return [
    'B24_APPLICATION_SCOPE' => explode(',', env('B24_APPLICATION_SCOPE')),
    'B24_APPLICATION_ID' => env('B24_APPLICATION_ID'),
    'B24_APPLICATION_SECRET' => env('B24_APPLICATION_SECRET'),
    'DOMAIN' => env('B24_DOMAIN'),
    'REDIRECT_URI' => env('B24_REDIRECT_URI', 'bitrix-redirect-uri')
];