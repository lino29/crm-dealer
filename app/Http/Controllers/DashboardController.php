<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function adminIndex(\App\Services\DashboardStatisticService $statsService)
    {
        $stats = $statsService->getAdminStats();
        return view('admin.dashboard', compact('stats'));
    }

    public function leaderIndex(\App\Services\DashboardStatisticService $statsService)
    {
        $stats = $statsService->getLeaderStats();
        return view('leader.dashboard', compact('stats'));
    }
}
