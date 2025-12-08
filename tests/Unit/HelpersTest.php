<?php

namespace Tests\Unit;

use Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_resolve_thumbnail_url_external_returns_same(): void
    {
        $url = 'https://example.com/img.jpg';
        $this->assertSame($url, resolve_thumbnail_url($url));
    }

    public function test_resolve_thumbnail_url_storage_path_prefixed(): void
    {
        $path = 'thumbs/img.jpg';
        $this->assertStringEndsWith('/storage/thumbs/img.jpg', resolve_thumbnail_url($path));
    }

    public function test_resolve_thumbnail_url_fallback_used(): void
    {
        $fallback = 'https://cdn.example.com/fallback.jpg';
        $this->assertSame($fallback, resolve_thumbnail_url(null, $fallback));
    }

    public function test_resolve_thumbnail_url_default_used(): void
    {
        $result = resolve_thumbnail_url(null, null);
        $this->assertStringContainsString('template/assets/static/images/samples/origami.jpg', $result);
    }
}
