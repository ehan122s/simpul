<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Category;
use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalPenyuluh = User::where('role', 'penyuluh')->count();
        $totalActivities = Activity::count();
        $totalCategories = Category::count();
        $totalMateri = Schema::hasTable('materials') ? Material::count() : 0;

        $latestActivities = Activity::with(['user', 'category'])
            ->latest('activity_date')
            ->take(5)
            ->get();

        $months = collect(range(5, 0))->map(fn ($monthsAgo) => now()->subMonths($monthsAgo));

        $laporanPerBulan = $months->map(fn ($date) => Activity::whereYear('activity_date', $date->year)
            ->whereMonth('activity_date', $date->month)
            ->count())
            ->toArray();

        $topCategories = Category::withCount('activities')
            ->orderByDesc('activities_count')
            ->take(5)
            ->get();

        $topPenyuluh = User::where('role', 'penyuluh')
            ->withCount('activities')
            ->orderByDesc('activities_count')
            ->take(5)
            ->get();

        $chartData = [
            'bulan' => $months->map(fn ($date) => $date->format('M'))->toArray(),
            'laporan_per_bulan' => $laporanPerBulan,
            'kategori_labels' => $topCategories->pluck('name')->toArray(),
            'kategori_values' => $topCategories->pluck('activities_count')->toArray(),
            'penyuluh_labels' => $topPenyuluh->pluck('name')->toArray(),
            'penyuluh_values' => $topPenyuluh->pluck('activities_count')->toArray(),
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPenyuluh',
            'totalActivities',
            'totalCategories',
            'totalMateri',
            'latestActivities',
            'chartData'
        ));
    }
}