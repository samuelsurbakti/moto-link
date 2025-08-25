<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

Route::get('/themes/{path}', function ($path) {
    $fullPath = storage_path('app/themes/' . $path);

    if (!File::exists($fullPath)) {
        abort(404);
    }

    // Validasi berdasarkan ekstensi
    $allowedExtensions = ['css', 'js', 'svg', 'png', 'jpg', 'jpeg', 'woff', 'woff2', 'json'];
    $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions)) {
        abort(403, 'Unauthorized asset access');
    }

    // Tentukan MIME secara manual
    $mimeMap = [
        'css'   => 'text/css',
        'js'    => 'application/javascript',
        'svg'   => 'image/svg+xml',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'json'  => 'application/json',
    ];

    $mime = $mimeMap[$extension] ?? 'application/octet-stream';

    return Response::file($fullPath, [
        'Content-Type' => $mime,
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*');
