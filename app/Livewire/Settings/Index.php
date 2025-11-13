<?php

namespace App\Livewire\Settings;

use App\Livewire\Traits\Alert;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
    use Alert;

    public string $app_name = 'DPanel';
    public string $app_url = 'http://localhost';
    public string $app_timezone = 'UTC';
    public string $app_locale = 'en';
    public bool $maintenance_mode = false;

    public string $mail_driver = 'smtp';
    public string $mail_host = 'smtp.mailtrap.io';
    public string $mail_port = '2525';
    public string $mail_username = '';
    public string $mail_password = '';
    public string $mail_from_address = 'noreply@dpanel.com';
    public string $mail_from_name = 'DPanel';

    public function mount(): void
    {
        // Load demo values
        $this->app_name = config('app.name', 'DPanel');
        $this->app_url = config('app.url', 'http://localhost');
        $this->app_timezone = config('app.timezone', 'UTC');
        $this->app_locale = config('app.locale', 'en');
    }

    public function render(): View
    {
        return view('livewire.settings.index');
    }

    public function saveGeneral(): void
    {
        $this->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'app_timezone' => 'required|string',
            'app_locale' => 'required|string',
        ]);

        // This is a demo - in production, you would save to .env or database
        $this->success('General settings saved successfully (Demo Mode)');
    }

    public function saveMail(): void
    {
        $this->validate([
            'mail_driver' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        // This is a demo - in production, you would save to .env or database
        $this->success('Mail settings saved successfully (Demo Mode)');
    }

    public function toggleMaintenance(): void
    {
        $this->maintenance_mode = !$this->maintenance_mode;

        $status = $this->maintenance_mode ? 'enabled' : 'disabled';
        $this->success("Maintenance mode {$status} (Demo Mode)");
    }
}
