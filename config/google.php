<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Drive Configuration
    |--------------------------------------------------------------------------
    |
    | Untuk menggunakan fitur upload ke Google Drive, Anda perlu:
    | 1. Buat project di https://console.cloud.google.com
    | 2. Enable Google Drive API
    | 3. Buat Service Account -> Create Key -> Download JSON
    | 4. Copy isi JSON ke file storage/app/google-drive-credentials.json
    | 5. Copy email service account, share folder Drive tujuan dengan email tsb
    |
    */

    'drive' => [
        'credentials_path' => storage_path('app/google-drive-credentials.json'),
        'folder_id' => env('GOOGLE_DRIVE_FOLDER_ID', null),
    ],
];