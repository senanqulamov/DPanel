<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleSwitchTest extends TestCase
{
    public function test_can_switch_to_valid_locale(): void
    {
        $response = $this->get('/lang/es');
        $response->assertRedirect();
        $this->assertSame('es', session('locale'));
        $response->assertCookie('locale', 'es');
    }

    public function test_invalid_locale_falls_back_to_default(): void
    {
        $fallback = config('languages.fallback', 'en');
        $response = $this->get('/lang/xx');
        $response->assertRedirect();
        $this->assertSame($fallback, session('locale'));
    }

    /**
     * @dataProvider supportedLocalesProvider
     */
    public function test_can_switch_to_all_supported_locales(string $locale): void
    {
        $response = $this->get("/lang/{$locale}");
        $response->assertRedirect();
        $this->assertSame($locale, session('locale'));
        $response->assertCookie('locale', $locale);
    }

    public static function supportedLocalesProvider(): array
    {
        return [
            'English' => ['en'],
            'Spanish' => ['es'],
            'French' => ['fr'],
            'German' => ['de'],
            'Turkish' => ['tr'],
        ];
    }
}
