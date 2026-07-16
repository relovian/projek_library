<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GoogleDriveService
{
   
    public function uploadDrive($file, $folderId) 
    {
        $fileName = $file->getClientOriginalName();
        
        // 1. Dapatkan service
        $adapter = Storage::disk('google')->getAdapter();
        $service = $adapter->getService();

        // 2. CEK DULU apakah file sudah ada
        $existingFiles = $service->files->listFiles([
            'q' => "'$folderId' in parents and name = '$fileName' and trashed = false",
            'fields' => 'files(id, webViewLink)'
        ]);

        if (count($existingFiles->getFiles()) > 0) {
            $existingFile = $existingFiles->getFiles()[0];
            return [
                'id' => $existingFile->getId(),
                'link' => $existingFile->webViewLink,
                'is_duplicate' => true // Tambahkan flag ini
            ];
        }

        // 3. Jika tidak ada, baru upload
        $fileContent = file_get_contents($file->getRealPath());
        
        $fileMetadata = new \Google\Service\Drive\DriveFile([
            'name' => $fileName,
            'parents' => [$folderId] 
        ]);
        
        $createdFile = $service->files->create($fileMetadata, [
            'data' => $fileContent,
            'uploadType' => 'media',
            'fields' => 'id, webViewLink'
        ]);

        return [
            'id'   => $createdFile->id,
            'link' => $createdFile->webViewLink,
            'is_duplicate' => false
        ];
    }

}

