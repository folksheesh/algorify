<?php

namespace App\Helpers;

/**
 * HTML Sanitizer for XSS Protection
 * Allows only safe HTML tags and removes dangerous attributes
 */
class HtmlSanitizer
{
    /**
     * List of allowed HTML tags
     */
    private static array $allowedTags = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'strike',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'ul', 'ol', 'li',
        'blockquote', 'pre', 'code',
        'a', 'img',
        'table', 'thead', 'tbody', 'tr', 'th', 'td',
        'div', 'span',
        'hr',
    ];

    /**
     * List of allowed attributes per tag
     */
    private static array $allowedAttributes = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
        '*' => ['class', 'id', 'style'],
    ];

    /**
     * Dangerous CSS properties to remove
     */
    private static array $dangerousCss = [
        'expression',
        'javascript:',
        'vbscript:',
        'behavior',
        '-moz-binding',
    ];

    /**
     * Sanitize HTML content
     */
    public static function sanitize(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // Remove script tags and their content
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        
        // Remove style tags and their content  
        $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);
        
        // Remove on* event handlers
        $html = preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace('/\s+on\w+\s*=\s*[^\s>]*/i', '', $html);
        
        // Remove javascript: and data: URLs in href/src
        $html = preg_replace('/href\s*=\s*["\']?\s*javascript:/i', 'href="', $html);
        $html = preg_replace('/src\s*=\s*["\']?\s*javascript:/i', 'src="', $html);
        $html = preg_replace('/href\s*=\s*["\']?\s*data:/i', 'href="', $html);
        
        // Remove dangerous CSS
        foreach (self::$dangerousCss as $dangerous) {
            $html = preg_replace('/style\s*=\s*["\'][^"\']*' . preg_quote($dangerous, '/') . '[^"\']*["\']/i', '', $html);
        }
        
        // Strip tags that are not allowed
        $allowedTagsString = '<' . implode('><', self::$allowedTags) . '>';
        $html = strip_tags($html, $allowedTagsString);
        
        return $html;
    }

    /**
     * Clean and encode for safe output
     */
    public static function clean(?string $text): string
    {
        if (empty($text)) {
            return '';
        }

        return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
