<?php
$content = file_get_contents('resources/views/layouts/public.blade.php');
$lines = explode("\n", $content);
foreach ($lines as $idx => $line) {
    if (strpos($line, 'switch.locale') !== false || strpos($line, 'Locale') !== false || strpos($line, 'हिंदी') !== false || strpos($line, 'हिन्दी') !== false) {
        echo ($idx + 1) . ": " . trim($line) . "\n";
    }
}
