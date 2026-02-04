<?php
function base_url($url = null)
{
    // Prefer explicit env var when available
    $env_base = getenv('BASE_URL');

    if ($env_base && is_string($env_base) && trim($env_base) !== '') {
        $base = rtrim(trim($env_base), '/');
    } else {
        // Derive from current request as a sensible default
        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            $scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'];
        } else {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
                ? 'https'
                : 'http';
        }
        if (!empty($_SERVER['HTTP_HOST'])) {
            $base = $scheme . '://' . $_SERVER['HTTP_HOST'];
        } else {
            // Fallback for CLI or unknown host
            $base = 'http://konsultasi_bkn.test';
        }
        $base = rtrim($base, '/');
    }

    if ($url !== null && $url !== '') {
        return $base . '/' . ltrim($url, '/');
    }
    return $base;
}

session_start();
date_default_timezone_set('Asia/Makassar');

// Simple auth + role gate
if (php_sapi_name() !== 'cli') {
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $path = parse_url($uri, PHP_URL_PATH);
    if ($path) {
        // Require login for protected areas
        if (strpos($path, '/admin/') !== false || strpos($path, '/user/') !== false) {
            if (empty($_SESSION['role'])) {
                header('Location: ' . base_url('login.php'));
                exit;
            }
        }

        // Role-specific gates
        if (strpos($path, '/admin/respon_konsultasi/') !== false) {
            if (!in_array($_SESSION['role'], ['Admin', 'Konselor'])) {
                header('Location: ' . base_url('login.php'));
                exit;
            }
        } elseif (strpos($path, '/admin/') !== false) {
            if ($_SESSION['role'] !== 'Admin') {
                header('Location: ' . base_url('login.php'));
                exit;
            }
        } elseif (strpos($path, '/user/') !== false) {
            if ($_SESSION['role'] !== 'User') {
                header('Location: ' . base_url('login.php'));
                exit;
            }
        }
    }
}
