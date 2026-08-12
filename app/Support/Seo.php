<?php

namespace App\Support;

use Illuminate\Support\Str;

class Seo
{
    public static function ensureBrandInTitle(?string $title, ?string $brand = 'Arogio'): string
    {
        $title = trim((string) $title);
        $brand = trim((string) $brand);
        $brandRoot = trim((string) preg_replace('/\s*-\s*.*/u', '', $brand));

        if ($title === '' || $brand === '') {
            return $title;
        }

        if (mb_strtolower($title) === mb_strtolower($brand)) {
            return $brand;
        }

        if (str_contains(mb_strtolower($title), mb_strtolower($brand))) {
            return $title;
        }

        if ($brandRoot !== '' && preg_match('/(\||-)\s*' . preg_quote($brandRoot, '/') . '\s*$/iu', $title)) {
            return preg_replace('/' . preg_quote($brandRoot, '/') . '\s*$/iu', $brand, $title) ?? $title;
        }

        if ($brandRoot !== '' && mb_strtolower($title) === mb_strtolower($brandRoot)) {
            return $brand;
        }

        return $title . ' | ' . $brand;
    }

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

    public static function keywords(array $items, int $maxItems = 12): string
    {
        $keywords = collect($items)
            ->flatten()
            ->map(static fn ($item) => self::cleanText((string) $item, 80))
            ->filter()
            ->unique(static fn ($item) => mb_strtolower($item))
            ->take($maxItems)
            ->values();

        return $keywords->implode(', ');
    }
}
