<?php
// ── Load .env file for local development ──────────────────────────────────
// On Railway (production), env vars are injected by the platform directly.
// Locally, they are read from the .env file in the project root.
function load_env(string $path): void {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue; // skip comments
        if (!str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);
        if (!array_key_exists($key, $_ENV) && !array_key_exists($key, $_SERVER)) {
            putenv("$key=$value");
            $_ENV[$key]    = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// Load .env from project root (two levels up from includes/)
load_env(dirname(__DIR__) . '/.env');

// ── Application Constants ──────────────────────────────────────────────────
define('GEMINI_API_KEY', getenv('GEMINI_API_KEY') ?: '');
define('GEMINI_API_URL', getenv('GEMINI_API_URL') ?: 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent');

// ── Utility ────────────────────────────────────────────────────────────────
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}