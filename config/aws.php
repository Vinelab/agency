<?php

return [
    's3' => [
        'bucket' => env('AWS_S3_BUCKET'),
        'credentials' => [
            'key' => env('AWS_ACCESS_KEY'),
            'secret' => env('AWS_ACCESS_SECRET')
        ]
    ]
];
