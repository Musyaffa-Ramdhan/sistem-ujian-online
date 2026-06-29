<?php

namespace App\Listeners;

use App\Models\LoginActivity;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class LogSuccessfulLogin
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event)
    {
        $user = $event->user;

        // Ensure user has a role relation loaded or available
        // If system uses different method for role, adjust here.
        // Assuming user->id_role exists based on our previous work.

        if ($user && isset($user->id_role)) {
            LoginActivity::create([
                'id_user' => $user->id_user,
                'id_role' => $user->id_role,
                'ip_address' => $this->request->ip(),
                'login_at' => now(),
            ]);
        }
    }
}
