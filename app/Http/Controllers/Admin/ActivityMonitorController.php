<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityMonitorController extends Controller
{
    public function index(Request $request)
    {
        // Fitur Monitoring: Admin bisa memfilter kegiatan berdasarkan nama Penyuluh
        $query = Activity::with(['user', 'category'])->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $activities = $query->paginate(15);
        $penyuluhList = User::where('role', 'penyuluh')->get();

        return view('admin.monitor.index', compact('activities', 'penyuluhList'));
    }

    public function show($id)
    {
        $activity = Activity::with(['user', 'category', 'photos', 'documents'])->findOrFail($id);
        return view('admin.monitor.show', compact('activity'));
    }
}