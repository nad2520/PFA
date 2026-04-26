<?php
declare(strict_types=1);

if (!function_exists('lx_normalize_web_path')) {
    function lx_normalize_web_path(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#/+#', '/', $path) ?? $path;

        return $path;
    }
}

if (!function_exists('lx_trim_root_index')) {
    function lx_trim_root_index(string $scriptName): string
    {
        $scriptName = lx_normalize_web_path($scriptName);

        if (str_ends_with($scriptName, '/index.php')) {
            return substr($scriptName, 0, -10);
        }

        return rtrim($scriptName, '/');
    }
}

if (!function_exists('lx_app_base_url')) {
    function lx_app_base_url(): string
    {
        $base = lx_trim_root_index((string)($_SERVER['SCRIPT_NAME'] ?? '/PFA/index.php'));

        if ($base === '' || $base === '.') {
            return '/PFA';
        }

        if (str_ends_with($base, '/public')) {
            $base = substr($base, 0, -7);
        }

        return rtrim($base, '/');
    }
}

if (!function_exists('lx_public_base_url')) {
    function lx_public_base_url(): string
    {
        return lx_app_base_url() . '/public';
    }
}

if (!function_exists('lx_app_href')) {
    function lx_app_href(string $path = ''): string
    {
        $base = lx_app_base_url();

        if ($path === '' || $path === '/') {
            return $base . '/';
        }

        if ($path[0] === '?') {
            return $base . '/index.php' . $path;
        }

        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('lx_public_asset')) {
    function lx_public_asset(string $pathUnderPublic): string
    {
        return lx_public_base_url() . '/' . ltrim(lx_normalize_web_path($pathUnderPublic), '/');
    }
}

if (!function_exists('lx_main_css_href')) {
    function lx_main_css_href(): string
    {
        $fs = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR
            . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'main.css';
        $v = is_file($fs) ? (string)filemtime($fs) : (string)time();

        return lx_public_asset('assets/css/user/main.css') . '?v=' . $v;
    }
}

if (!function_exists('lx_public_js_href')) {
    function lx_public_js_href(string $pathUnderPublicJs): string
    {
        $rel = ltrim(lx_normalize_web_path($pathUnderPublicJs), '/');
        $fs = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $rel);
        $v = is_file($fs) ? (string)filemtime($fs) : (string)time();

        return lx_public_asset($rel) . '?v=' . $v;
    }
}
