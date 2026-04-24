<?php
declare(strict_types=1);

/**
 * Resolves URLs for static files under /public so they work whether the user opens
 * /PFA/index.php (project root) or /PFA/public/index.php (front controller in public/).
 */
if (!function_exists('lx_public_base_url')) {
    function lx_public_base_url(): string
    {
        $sfn = str_replace('\\', '/', (string)($_SERVER['SCRIPT_FILENAME'] ?? ''));
        $inPublicFc = str_contains($sfn, '/public/index.php');

        $scriptDir = dirname(str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? '')));
        if ($scriptDir === '/' || $scriptDir === '.') {
            $scriptDir = '';
        }

        if ($scriptDir !== '' && str_ends_with($scriptDir, '/public')) {
            return $scriptDir;
        }
        if ($inPublicFc) {
            return $scriptDir;
        }

        return ($scriptDir === '' ? '' : $scriptDir) . '/public';
    }
}

if (!function_exists('lx_public_asset')) {
    /** @param string $pathUnderPublic e.g. "assets/js/user_app.js" */
    function lx_public_asset(string $pathUnderPublic): string
    {
        $pathUnderPublic = '/' . ltrim(str_replace('\\', '/', $pathUnderPublic), '/');

        return lx_public_base_url() . $pathUnderPublic;
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
    /** @param string $pathUnderPublicJs e.g. "assets/js/user_app.js" */
    function lx_public_js_href(string $pathUnderPublicJs): string
    {
        $rel = ltrim(str_replace('\\', '/', $pathUnderPublicJs), '/');
        $fs = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $rel);
        $v = is_file($fs) ? (string)filemtime($fs) : (string)time();

        return lx_public_asset($rel) . '?v=' . $v;
    }
}
