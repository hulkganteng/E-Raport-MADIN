<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Process\Process;

class ChromePdfRenderer
{
    public function streamView(string $view, array $data, string $filename): BinaryFileResponse
    {
        $tempDir = storage_path('app/pdf-temp');
        File::ensureDirectoryExists($tempDir);

        $htmlPath = $this->tempPath($tempDir, 'html');
        $pdfPath = $this->tempPath($tempDir, 'pdf');
        $userDataDir = $this->tempDirectory($tempDir, 'chrome-profile_');

        File::put($htmlPath, view($view, $data)->render());

        $process = new Process([
            $this->resolveChromeBinary(),
            '--headless=new',
            '--disable-gpu',
            '--disable-crash-reporter',
            '--disable-breakpad',
            '--no-first-run',
            '--no-default-browser-check',
            '--allow-file-access-from-files',
            '--no-pdf-header-footer',
            '--user-data-dir=' . $userDataDir,
            '--print-to-pdf=' . $pdfPath,
            $this->toFileUrl($htmlPath),
        ]);

        $process->setTimeout(120);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->cleanupTempFiles([$htmlPath, $pdfPath], [$userDataDir]);

            throw new \RuntimeException(trim($process->getErrorOutput() ?: $process->getOutput()) ?: 'Gagal menjalankan Chrome PDF renderer.');
        }

        $this->waitForPdf($pdfPath);
        File::delete($htmlPath);
        File::deleteDirectory($userDataDir);

        $response = response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
        ]);

        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $filename);
        $response->deleteFileAfterSend(true);

        return $response;
    }

    public function toFileUrl(string $path): string
    {
        return 'file:///' . str_replace('\\', '/', $path);
    }

    private function tempPath(string $dir, string $extension): string
    {
        $path = tempnam($dir, 'raport_');

        if ($path === false) {
            throw new \RuntimeException('Gagal membuat file sementara.');
        }

        $target = $path . '.' . $extension;
        File::move($path, $target);

        return $target;
    }

    private function tempDirectory(string $baseDir, string $prefix): string
    {
        do {
            $path = $baseDir . DIRECTORY_SEPARATOR . $prefix . bin2hex(random_bytes(8));
        } while (is_dir($path));

        File::ensureDirectoryExists($path);

        return $path;
    }

    private function waitForPdf(string $pdfPath): void
    {
        for ($attempt = 0; $attempt < 10; $attempt++) {
            clearstatcache(true, $pdfPath);

            if (File::exists($pdfPath) && File::size($pdfPath) > 0) {
                return;
            }

            usleep(200000);
        }

        File::delete($pdfPath);

        throw new \RuntimeException('File PDF tidak berhasil dibuat oleh Chrome.');
    }

    private function cleanupTempFiles(array $files = [], array $directories = []): void
    {
        File::delete(array_filter($files));

        foreach ($directories as $directory) {
            if ($directory) {
                File::deleteDirectory($directory);
            }
        }
    }

    private function resolveChromeBinary(): string
    {
        $candidates = array_filter([
            getenv('CHROME_PDF_BINARY') ?: null,
            'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
            'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
        ]);

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        throw new \RuntimeException('Chrome/Edge tidak ditemukan. Set CHROME_PDF_BINARY di environment server.');
    }
}
