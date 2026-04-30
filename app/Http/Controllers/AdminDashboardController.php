<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::count();

        return Inertia::render('Dashboard', [
            'users' => $users
        ]);
    }
}
