<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Models\Guru;

$guru = Guru::where('nama', 'Guru PPKN')->first();

if (! $guru) {
    echo "Guru PPKN not found.\n";
    exit(1);
}

echo 'Guru ID: '.$guru->id_guru."\n";
echo 'Guru id_user: '.$guru->id_user."\n";

$user = $guru->user;

if ($user) {
    echo "User Found!\n";
    echo 'User ID: '.$user->id_user."\n";
    echo 'User Email: '.$user->email."\n";
} else {
    echo "User relation returned NULL.\n";
    // Check manual query
    $manualUser = \App\Models\User::find($guru->id_user);
    if ($manualUser) {
        echo "Manual User Find OK - Relation definition might be wrong.\n";
    } else {
        echo "User record strictly missing from database.\n";
    }
}
