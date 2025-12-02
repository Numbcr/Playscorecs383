<?php
/**
 * Translation Helper Script
 * Run: php translate-helper.php
 * This will show you all hardcoded English text in your views
 */

$viewsPath = __DIR__ . '/resources/views';

function findHardcodedText($file) {
    $content = file_get_contents($file);
    
    // Find text between HTML tags that's not already translated
    preg_match_all('/>([A-Za-z][A-Za-z\s]{2,})</i', $content, $matches);
    
    $hardcoded = [];
    foreach ($matches[1] as $text) {
        $text = trim($text);
        if ($text && !str_contains($text, '{{') && !str_contains($text, '@')) {
            $hardcoded[] = $text;
        }
    }
    
    return array_unique($hardcoded);
}

function scanDirectory($dir) {
    $results = [];
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir)
    );
    
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $hardcoded = findHardcodedText($file->getPathname());
            if (!empty($hardcoded)) {
                $results[$file->getPathname()] = $hardcoded;
            }
        }
    }
    
    return $results;
}

echo "Scanning views for hardcoded text...\n\n";
$results = scanDirectory($viewsPath);

echo "Files with hardcoded English text:\n";
echo "=" . str_repeat("=", 50) . "\n\n";

foreach ($results as $file => $texts) {
    echo basename($file) . ":\n";
    foreach ($texts as $text) {
        echo "  - \"$text\"\n";
    }
    echo "\n";
}

echo "\nTo translate these:\n";
echo "1. Add to lang/en/messages.php and lang/ar/messages.php\n";
echo "2. Replace in views with: {{ __('messages.key') }}\n";
