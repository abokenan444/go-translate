<?php

namespace App\Http\Controllers;

use App\Support\AccountDashboard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DashboardRedirectController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return redirect(AccountDashboard::pathFor($user));
    }
}
