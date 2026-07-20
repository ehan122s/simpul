<?php

namespace App\Http\Controllers\Penyuluh;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Mengambil statistik khusus untuk penyuluh yang sedang login
        $totalActivities = Activity::where('user_id', $userId)->count();
        $recentActivities = Activity::where('user_id', $userId)
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('penyuluh.dashboard', compact('totalActivities', 'recentActivities'));
    }
}