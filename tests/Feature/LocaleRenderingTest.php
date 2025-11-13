<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocaleRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_label_renders_in_spanish_after_switch(): void
    {
        $user = User::factory()->create();

        // Switch locale to Spanish
        $this->get('/lang/es');

        $this->actingAs($user);

        // Visit users index page (auth protected) where sidebar is rendered
        $response = $this->get('/users');

        // Sidebar items
        $response->assertSee('Panel'); // Dashboard translated
        $response->assertSee('Usuarios'); // Users translated
    }

    /**
     * @dataProvider localeRenderingProvider
     */
    public function test_sidebar_renders_in_selected_locale(string $locale, string $dashboardText, string $usersText): void
    {
        $user = User::factory()->create();

        // Switch locale
        $this->get("/lang/{$locale}");

        $this->actingAs($user);

        // Visit users index page
        $response = $this->get('/users');

        // Assert translated sidebar items
        $response->assertSee($dashboardText);
        $response->assertSee($usersText);
    }

    public static function localeRenderingProvider(): array
    {
        return [
            'English' => ['en', 'Dashboard', 'Users'],
            'Spanish' => ['es', 'Panel', 'Usuarios'],
            'French' => ['fr', 'Tableau de bord', 'Utilisateurs'],
            'German' => ['de', 'Übersicht', 'Benutzer'],
            'Turkish' => ['tr', 'Kontrol Paneli', 'Kullanıcılar'],
        ];
    }

    /**
     * @dataProvider tableHeadersProvider
     */
    public function test_users_table_headers_render_in_selected_locale(string $locale, array $expectedHeaders): void
    {
        $user = User::factory()->create();

        // Switch locale
        $this->get("/lang/{$locale}");

        $this->actingAs($user);

        // Visit users index page
        $response = $this->get('/users');

        // Assert all expected table headers are present
        foreach ($expectedHeaders as $header) {
            $response->assertSee($header);
        }
    }

    public static function tableHeadersProvider(): array
    {
        return [
            'English' => ['en', ['Name', 'E-mail', 'Created', 'Actions']],
            'Spanish' => ['es', ['Nombre', 'Correo electrónico', 'Creado', 'Acciones']],
            'French' => ['fr', ['Nom', 'E-mail', 'Créé', 'Actions']],
            'German' => ['de', ['Name', 'E-Mail', 'Erstellt', 'Aktionen']],
            'Turkish' => ['tr', ['İsim', 'E-posta', 'Oluşturuldu', 'İşlemler']],
        ];
    }
}
