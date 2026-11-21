<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cheque;
use App\Models\Bill;
use App\Models\Department;
use App\Models\BudgetRelease;

echo "Cheques count: " . Cheque::count() . "\n";
echo "Cheques xyz_commission sum: " . Cheque::sum('xyz_commission') . "\n";
echo "Cheques our_commission sum: " . Cheque::sum('our_commission') . "\n";
echo "Cheques other_commission sum: " . Cheque::sum('other_commission') . "\n";
echo "Cheques amount sum: " . Cheque::sum('amount') . "\n";

foreach (Cheque::all() as $c) {
    echo "ID: {$c->id}, doc_number: {$c->doc_number}, amount: {$c->amount}, xyz_commission: {$c->xyz_commission}, our_commission: {$c->our_commission}, other_commission: {$c->other_commission}\n";
}
