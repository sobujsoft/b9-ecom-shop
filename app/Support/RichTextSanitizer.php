<?php

namespace App\Support;

class RichTextSanitizer
{
    private const ALLOWED_TAGS = '<p><br><strong><b><em><i><u><s><strike><h2><h3><h4><ul><ol><li><a><blockquote><hr>';

    /**
     * Sanitize rich text content before it is stored.
     */
    public static function clean(?string $html): ?string
    {
        if (! filled($html)) {
            return null;
        }

        $withoutExecutableBlocks = preg_replace(
            '/<(script|style)\b[^>]*>.*?<\/\1>/is',
            '',
            $html,
        ) ?? $html;

        $cleaned = trim(strip_tags($withoutExecutableBlocks, self::ALLOWED_TAGS));

        return $cleaned === '' ? null : $cleaned;
    }

    /**
     * Prepare rich text for safe display on the storefront.
     */
    public static function forDisplay(?string $content): string
    {
        if (! filled($content)) {
            return '';
        }

        if ($content !== strip_tags($content)) {
            return self::clean($content) ?? '';
        }

        return collect(preg_split('/\R{2,}/', trim($content)) ?: [])
            ->filter(fn (string $paragraph): bool => trim($paragraph) !== '')
            ->map(fn (string $paragraph): string => '<p>'.e(trim($paragraph)).'</p>')
            ->join('');
    }
}
