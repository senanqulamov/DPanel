<?php

namespace App\Livewire\Logs;

use App\Models\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class View extends Component
{
    public ?Log $log = null;

    public bool $modal = false;

    public function render()
    {
        return view('livewire.logs.view');
    }

    #[On('load::log')]
    public function load($log): void
    {
        // Accept either a Log ID or Log model
        if (is_numeric($log)) {
            $this->log = Log::with('user')->find($log);
        } elseif ($log instanceof Log) {
            $this->log = $log->load('user');
        }

        if ($this->log) {
            $this->modal = true;
        }
    }

    public function close(): void
    {
        $this->modal = false;
        $this->log = null;
    }
}
