<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GoogleDriveService
{
    protected Client $client;
    protected Drive $drive;
    protected ?string $folderId;

    public function __construct()
    {
        $this->folderId = config('google.drive.folder_id', env('GOOGLE_DRIVE_FOLDER_ID'));
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/oauth-credentials.json'));
        $this->client->addScope(Drive::DRIVE_FILE);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');

        if (Storage::exists('google-token.json')) {
            $token = json_decode(Storage::get('google-token.json'), true);
            $this->client->setAccessToken($token);

            if ($this->client->isAccessTokenExpired()) {
                $refreshToken = $token['refresh_token'] ?? null;
                if ($refreshToken) {
                    $newToken = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
                    $newToken['refresh_token'] = $refreshToken;
                    Storage::put('google-token.json', json_encode($newToken));
                    $this->client->setAccessToken($newToken);
                }
            }
        }

        $this->drive = new Drive($this->client);
    }

    /**
     * Upload file ke Google Drive.
     *
     * @param UploadedFile $file
     * @param string $fileName Nama file yang akan disimpan di Drive
     * @return string|null URL file yang bisa diakses publik
     */
    public function upload(UploadedFile $file, string $fileName): ?string
    {
        try {
            $driveFile = new DriveFile();
            $driveFile->setName($fileName);
            $driveFile->setMimeType($file->getMimeType());

            // Set folder tujuan jika ada
            if (!empty($this->folderId)) {
                $driveFile->setParents([$this->folderId]);
            }

            // Upload file
            $content = file_get_contents($file->getRealPath());
            $uploadedFile = $this->drive->files->create($driveFile, [
                'data' => $content,
                'mimeType' => $file->getMimeType(),
                'uploadType' => 'multipart',
                'fields' => 'id, name, webViewLink, webContentLink',
            ]);

            // Set permission agar bisa diakses oleh siapa saja yang punya link
            $this->makePublic($uploadedFile->id);

            // Simpan log
            Log::info("Google Drive upload success: {$uploadedFile->name} (ID: {$uploadedFile->id})");

            // Return WebViewLink
            return $uploadedFile->webViewLink;

        } catch (\Exception $e) {
            Log::error("Google Drive upload failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Upload file dari path lokal ke Google Drive.
     */
    public function uploadFromPath(string $localPath, string $fileName, string $mimeType = 'application/octet-stream'): ?string
    {
        try {
            $driveFile = new DriveFile();
            $driveFile->setName($fileName);
            $driveFile->setMimeType($mimeType);

            if (!empty($this->folderId)) {
                $driveFile->setParents([$this->folderId]);
            }

            $content = file_get_contents($localPath);
            $uploadedFile = $this->drive->files->create($driveFile, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id, name, webViewLink, webContentLink',
            ]);

            $this->makePublic($uploadedFile->id);

            return $uploadedFile->webViewLink;

        } catch (\Exception $e) {
            Log::error("Google Drive upload failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Buat file menjadi public (siapa saja dengan link bisa akses).
     */
    protected function makePublic(string $fileId): void
    {
        try {
            $permission = new \Google\Service\Drive\Permission();
            $permission->setType('anyone');
            $permission->setRole('reader');
            $this->drive->permissions->create($fileId, $permission);
        } catch (\Exception $e) {
            Log::warning("Gagal set public permission: " . $e->getMessage());
        }
    }

    /**
     * Hapus file dari Google Drive.
     */
    public function delete(string $fileId): bool
    {
        try {
            $this->drive->files->delete($fileId);
            return true;
        } catch (\Exception $e) {
            Log::error("Google Drive delete failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Copy file dari link Google Drive ke folder tujuan.
     *
     * @param string $driveLink URL file Google Drive
     * @return string|null URL file baru di folder tujuan
     */
    public function copyFromLink(string $driveLink): ?string
    {
        try {
            // Extract File ID dari link Google Drive
            $fileId = $this->extractFileId($driveLink);
            if (!$fileId) {
                throw new \Exception('Link Google Drive tidak valid. Format: https://drive.google.com/file/d/FILE_ID/view');
            }

            // Dapatkan detail file asli
            $originalFile = $this->drive->files->get($fileId, ['fields' => 'id, name, mimeType']);

            // Buat file baru di folder tujuan (copy)
            $newFile = new DriveFile();
            $newFile->setName($originalFile->name);
            $newFile->setMimeType($originalFile->mimeType);

            if (!empty($this->folderId)) {
                $newFile->setParents([$this->folderId]);
            }

            // Copy file
            $copiedFile = $this->drive->files->copy($fileId, $newFile, [
                'fields' => 'id, name, webViewLink, webContentLink',
            ]);

            // Set public permission
            $this->makePublic($copiedFile->id);

            Log::info("Google Drive copy success: {$copiedFile->name} (ID: {$copiedFile->id})");

            return $copiedFile->webViewLink;

        } catch (\Exception $e) {
            Log::error("Google Drive copy failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Extract File ID dari Google Drive URL.
     */
    protected function extractFileId(string $url): ?string
    {
        // Format: https://drive.google.com/file/d/FILE_ID/view
        preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches);
        
        if (isset($matches[1])) {
            return $matches[1];
        }

        // Format: https://drive.google.com/open?id=FILE_ID
        preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $matches);
        
        return $matches[1] ?? null;
    }

    /**
     * Cek koneksi ke Google Drive.
     */
    public function isConnected(): bool
    {
        try {
            $this->drive->files->listFiles(['pageSize' => 1]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}