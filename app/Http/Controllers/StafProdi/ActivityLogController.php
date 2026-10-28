<?php

namespace App\Http\Controllers\StafProdi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public static function saveLog($aksi, $target)
    {
        DB::table('activity_logs')->insert([
            'username'   => Auth::user()->username ?? 'System', 
            'aksi'       => $aksi,
            'target'     => $target,
            'ip_address' => request()->ip(),
            'created_at' => now()->timezone('Asia/Jakarta'),
            'updated_at' => now()->timezone('Asia/Jakarta'),
        ]);
    }

    public function index(Request $request)
    {
        $query = DB::table('activity_logs');

        // Filter Pencarian (Username, Aksi, atau Target)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('aksi', 'like', "%{$search}%")
                  ->orWhere('target', 'like', "%{$search}%");
            });
        }

        $activity_logs = $query->orderBy('created_at', 'desc')
                              ->paginate(20)
                              ->appends($request->query());

        return view('dashboard.staf-prodi.activity_logs', [
            'activity_logs' => $activity_logs,
            'activeMenu' => 'activity_logs'
        ]);
    }
}