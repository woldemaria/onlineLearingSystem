<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaStreamController extends Controller
{
    /**
     * Stream video or media files with HTTP 206 Range support (RFC 7233).
     * Enables chunked streaming, video timeline scrubbing, and instant buffering.
     */
    public function stream(Request $request, string $path)
    {
        // Prevent path traversal attacks
        $cleanPath = str_replace(['..', "\0"], '', $path);
        $cleanPath = ltrim($cleanPath, '/');

        // Check in storage/app/public/ or public/
        $candidates = [
            storage_path('app/public/' . $cleanPath),
            public_path('storage/' . $cleanPath),
            public_path($cleanPath),
        ];

        $filePath = null;
        foreach ($candidates as $candidate) {
            if (file_exists($candidate) && is_file($candidate)) {
                $filePath = $candidate;
                break;
            }
        }

        if (!$filePath) {
            return response()->json([
                'error' => 'Media file not found',
                'path' => $path,
            ], 404);
        }

        $size = filesize($filePath);
        $mime = mime_content_type($filePath) ?: 'video/mp4';

        // Fix video mime types if needed
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if ($ext === 'mp4') $mime = 'video/mp4';
        elseif ($ext === 'webm') $mime = 'video/webm';
        elseif ($ext === 'mov') $mime = 'video/quicktime';
        elseif ($ext === 'pdf') $mime = 'application/pdf';

        $range = $request->header('Range');

        if (!$range) {
            // Full file download / initial response
            return response()->file($filePath, [
                'Content-Type' => $mime,
                'Content-Length' => $size,
                'Accept-Ranges' => 'bytes',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        // Parse Range: bytes=start-end
        if (!preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $range, $matches)) {
            return response('Requested Range Not Satisfiable', 416, [
                'Content-Range' => "bytes */{$size}",
            ]);
        }

        $start = (int) $matches[1];
        $end = !empty($matches[2]) ? (int) $matches[2] : ($size - 1);

        // Cap chunk size to maximum 2MB for high-efficiency progressive streaming
        $maxChunk = 2 * 1024 * 1024; // 2MB
        if (($end - $start) > $maxChunk) {
            $end = $start + $maxChunk - 1;
        }

        if ($start > $end || $start >= $size) {
            return response('Requested Range Not Satisfiable', 416, [
                'Content-Range' => "bytes */{$size}",
            ]);
        }

        if ($end >= $size) {
            $end = $size - 1;
        }

        $length = $end - $start + 1;

        $response = new StreamedResponse(function () use ($filePath, $start, $length) {
            $fp = fopen($filePath, 'rb');
            if (!$fp) return;

            fseek($fp, $start);
            $bytesSent = 0;
            $bufferSize = 64 * 1024; // 64KB chunks

            while (!feof($fp) && $bytesSent < $length && connection_status() === CONNECTION_NORMAL) {
                $readSize = min($bufferSize, $length - $bytesSent);
                $buffer = fread($fp, $readSize);
                echo $buffer;
                flush();
                $bytesSent += strlen($buffer);
            }

            fclose($fp);
        }, 206);

        $response->headers->set('Content-Type', $mime);
        $response->headers->set('Content-Range', "bytes {$start}-{$end}/{$size}");
        $response->headers->set('Accept-Ranges', 'bytes');
        $response->headers->set('Content-Length', (string) $length);
        $response->headers->set('Cache-Control', 'public, max-age=86400');

        return $response;
    }
}
