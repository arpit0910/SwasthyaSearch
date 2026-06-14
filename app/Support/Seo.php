<?php

namespace App\Support;

use Illuminate\Support\Str;

class Seo
{
    public static function cleanText(?string $text, int $limit = 160): string
    {
        $value = html_entity_decode((string) $text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = strip_tags($value);
        $value = preg_replace('/\s+/u', ' ', $value ?? '');
        $value = trim((string) $value);

        if ($limit > 0) {
            return Str::limit($value, $limit, '');
        }

        return $value;
    }

    public static function toPhrase(array $items, string $conjunction = 'and'): string
    {
        $items = array_values(array_filter(array_map(
            static fn ($item) => trim((string) $item),
            $items
        )));

        $count = count($items);

        if ($count === 0) {
            return '';
        }

        if ($count === 1) {
            return $items[0];
        }

        if ($count === 2) {
            return $items[0] . ' ' . $conjunction . ' ' . $items[1];
        }

        $last = array_pop($items);

        return implode(', ', $items) . ', ' . $conjunction . ' ' . $last;
    }
}
