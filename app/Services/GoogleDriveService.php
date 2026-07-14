<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GoogleDriveService
{
   public function uploadDrive($file) 
   {
        $fileName = $file->getClientOriginalName();
        return Storage::disk('google')->put($fileName, File::get($file));
   }

}

