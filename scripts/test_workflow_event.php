<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WorkflowEvent;
use App\Models\Quote;

echo "Before: " . WorkflowEvent::count() . PHP_EOL;
$quote = Quote::first();
if (! $quote) {
    echo "No quote found\n";
    exit(0);
}

event(new App\Events\QuoteSubmitted($quote));

// small delay to allow any queued listeners if sync
sleep(1);

echo "After: " . WorkflowEvent::count() . PHP_EOL;
echo "Dispatched for quote id: " . $quote->id . PHP_EOL;
