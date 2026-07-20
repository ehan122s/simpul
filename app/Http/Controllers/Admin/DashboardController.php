<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil statistik global sistem SIMPUL untuk Admin
        $totalPenyuluh = User::where('role', 'penyuluh')->count();
        $totalActivities = Activity::count();
        $totalCategories = Category::count();
        
        $latestActivities = Activity::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('totalPenyuluh', 'totalActivities', 'totalCategories', 'latestActivities'));
    }
}