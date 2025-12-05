<?php
namespace Core;

class Assets
{
    private static ?array $manifest = null;
    private static ?bool $viteDev = null;

    private static function isViteDevServerRunning(): bool
    {
        if (self::$viteDev !== null) return self::$viteDev;
        $host = '127.0.0.1';
        $port = 5173;
        $errno = 0; $errstr = '';
        $fp = @fsockopen($host, $port, $errno, $errstr, 0.05);
        if ($fp) { fclose($fp); return self::$viteDev = true; }
        return self::$viteDev = false;
    }
    private static function loadManifest(): array
    {
        if (self::$manifest !== null) return self::$manifest;
        $path = __DIR__ . '/../../public/assets/build/manifest.json';
        if (is_file($path)) {
            $data = json_decode((string)file_get_contents($path), true);
            if (is_array($data)) return self::$manifest = $data;
        }
        return self::$manifest = [];
    }
    public static function vite(string $entry): string
    {
        if (self::isViteDevServerRunning()) {
            $dev = 'http://localhost:5173';
            $tags = [];
            $tags[] = '<script type="module" src="' . htmlspecialchars($dev . '/@vite/client', ENT_QUOTES) . '"></script>';
            $file = ($entry === 'app') ? 'resources/js/main.tsx' : $entry;
            $tags[] = '<script type="module" src="' . htmlspecialchars($dev . '/' . ltrim($file, '/'), ENT_QUOTES) . '"></script>';
            return implode("\n", $tags);
        }
        $m = self::loadManifest();
        $tags = [];
        $item = $m[$entry] ?? null;
        if ($item === null) {
            foreach ($m as $k => $v) {
                if (is_array($v) && isset($v['name']) && $v['name'] === $entry) { $item = $v; break; }
            }
        }
        if ($item === null) return '';
        if (!empty($item['css'])) {
            foreach ($item['css'] as $css) {
                $href = BASE_URL . 'assets/build/' . ltrim($css, '/');
                $tags[] = '<link rel="stylesheet" href="' . htmlspecialchars($href, ENT_QUOTES) . '">';
            }
        }
        if (!empty($item['file'])) {
            $src = BASE_URL . 'assets/build/' . ltrim($item['file'], '/');
            $tags[] = '<script type="module" src="' . htmlspecialchars($src, ENT_QUOTES) . '"></script>';
        }
        return implode("\n", $tags);
    }
}
