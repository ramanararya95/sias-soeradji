<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipAktif;
use App\Models\ArsipInaktif;
use App\Models\ArsipVital;
use App\Models\ArsipAlihmedia;
use App\Models\SuratTugas;
use App\Models\BeritaAcaraPemindahan;
use App\Models\BeritaAcaraPemusnahan;
use App\Models\BeritaAcaraAlihmedia;
use App\Models\Activity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            
            // Get statistics with caching for better performance
            $stats = $this->getCachedStats($user);
            
            // Get recent activities
            $recentActivities = $this->getRecentActivities($user);
            
            // Get online users
            $onlineUsers = $this->getOnlineUsers();
            
            // Get notifications count
            $unreadNotificationsCount = $user->notifications()->whereNull('read_at')->count();
            
            // Get quick stats for charts
            $monthlyStats = $this->getMonthlyStats($user);
            
            // Get recent archives
            $recentArchives = $this->getRecentArchives($user);
            
            return view('dashboard.index', compact(
                'user', 
                'stats', 
                'recentActivities', 
                'onlineUsers',
                'unreadNotificationsCount',
                'monthlyStats',
                'recentArchives'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            
            // Return default values if error occurs
            $user = auth()->user();
            $stats = $this->getDefaultStats();
            
            $recentActivities = collect([]);
            $onlineUsers = collect([]);
            $unreadNotificationsCount = 0;
            $monthlyStats = [];
            $recentArchives = collect([]);

        return view('dashboard.index', compact(
            'user', 
            'stats', 
            'recentActivities',
            'onlineUsers',
            'unreadNotificationsCount',
            'monthlyStats',
            'recentArchives'
        ));

        }
    }
    
    private function getCachedStats($user)
    {
        $cacheKey = 'dashboard_stats_' . $user->id;
        
        return cache()->remember($cacheKey, now()->addMinutes(5), function () use ($user) {
            return [
                'total_arsip_aktif' => $this->getArsipCount($user, 'aktif'),
                'total_arsip_inaktif' => $this->getArsipCount($user, 'inaktif'),
                'total_arsip_vital' => $this->getArsipCount($user, 'vital'),
                'total_arsip_alihmedia' => $this->getArsipCount($user, 'alihmedia'),
                'total_surat_tugas' => $this->getSuratTugasCount($user),
                'total_berita_pemindahan' => $this->getBeritaAcaraCount($user, 'pemindahan'),
                'total_berita_pemusnahan' => $this->getBeritaAcaraCount($user, 'pemusnahan'),
                'total_berita_alihmedia' => $this->getBeritaAcaraCount($user, 'alihmedia'),
                'growth' => $this->calculateGrowth($user),
            ];
        });
    }
    
    private function getArsipCount($user, $type)
    {
        $query = null;
        
        switch ($type) {
            case 'aktif':
                $query = ArsipAktif::query();
                break;
            case 'inaktif':
                $query = ArsipInaktif::query();
                break;
            case 'vital':
                $query = ArsipVital::query();
                break;
            case 'alihmedia':
                $query = ArsipAlihmedia::query();
                break;
        }
        
        if ($query && $user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }
        
        return $query ? $query->count() : 0;
    }
    
    private function getSuratTugasCount($user)
    {
        $query = SuratTugas::query();
        
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }
        
        return $query->count();
    }
    
    private function getBeritaAcaraCount($user, $type)
    {
        $query = null;
        
        switch ($type) {
            case 'pemindahan':
                $query = BeritaAcaraPemindahan::query();
                break;
            case 'pemusnahan':
                $query = BeritaAcaraPemusnahan::query();
                break;
            case 'alihmedia':
                $query = BeritaAcaraAlihmedia::query();
                break;
        }
        
        if ($query && $user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }
        
        return $query ? $query->count() : 0;
    }
    
    private function calculateGrowth($user)
    {
        $lastMonth = now()->subMonth();
        $thisMonth = now();
        
        // Calculate growth percentage (simplified)
        return [
            'arsip_aktif' => rand(5, 15), // Replace with actual calculation
            'arsip_inaktif' => rand(3, 10),
            'surat_tugas' => rand(10, 25),
            'berita_acara' => rand(2, 8),
        ];
    }
    
    private function getRecentActivities($user, $limit = 10)
    {
        $query = Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit);
            
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }
        
        return $query->get();
    }
    
    private function getOnlineUsers()
    {
        return User::where('last_activity_at', '>=', now()->subMinutes(5))
            ->with('profile')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'nama_lengkap' => $user->nama_lengkap,
                    'role' => $user->role,
                    'initials' => $this->getInitials($user->nama_lengkap),
                    'avatar_url' => $user->profile && $user->profile->foto ? 
                        asset('storage/profiles/' . $user->profile->foto) : null,
                    'last_activity_formatted' => 'Sekarang'
                ];
            });
    }
    
    private function getMonthlyStats($user)
    {
        // Get data for the last 6 months
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = [
                'month' => $month->format('M Y'),
                'arsip_aktif' => $this->getMonthlyArsipCount($user, 'aktif', $month),
                'arsip_inaktif' => $this->getMonthlyArsipCount($user, 'inaktif', $month),
                'surat_tugas' => $this->getMonthlySuratTugasCount($user, $month),
            ];
        }
        
        return $months;
    }
    
    private function getMonthlyArsipCount($user, $type, $month)
    {
        // Simplified calculation - replace with actual query
        return rand(5, 20);
    }
    
    private function getMonthlySuratTugasCount($user, $month)
    {
        // Simplified calculation - replace with actual query
        return rand(2, 10);
    }
    
    private function getRecentArchives($user, $limit = 5)
    {
        $archives = collect();
        
        // Get recent arsip aktif
        $arsipAktif = ArsipAktif::with('user')
            ->when($user->role !== 'admin', function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'Arsip Aktif',
                    'judul' => $item->judul,
                    'created_at' => $item->created_at,
                    'user' => $item->user
                ];
            });
        
        $archives = $archives->merge($arsipAktif);
        
        // Get recent surat tugas
        $suratTugas = SuratTugas::with('user')
            ->when($user->role !== 'admin', function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'Surat Tugas',
                    'judul' => $item->nomor_surat,
                    'created_at' => $item->created_at,
                    'user' => $item->user
                ];
            });
        
        $archives = $archives->merge($suratTugas);
        
        return $archives->sortByDesc('created_at')->take($limit);
    }
    
    private function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        
        return substr($initials, 0, 2);
    }
    
    private function getDefaultStats()
    {
        return [
            'total_arsip_aktif' => 0,
            'total_arsip_inaktif' => 0,
            'total_arsip_vital' => 0,
            'total_arsip_alihmedia' => 0,
            'total_surat_tugas' => 0,
            'total_berita_pemindahan' => 0,
            'total_berita_pemusnahan' => 0,
            'total_berita_alihmedia' => 0,
            'growth' => [
                'arsip_aktif' => 0,
                'arsip_inaktif' => 0,
                'surat_tugas' => 0,
                'berita_acara' => 0,
            ],
        ];
    }
    
    // API Methods for AJAX requests
    public function getStatsApi(Request $request)
    {
        $user = auth()->user();
        $stats = $this->getCachedStats($user);
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
    
    public function getActivitiesApi(Request $request)
    {
        $user = auth()->user();
        $activities = $this->getRecentActivities($user);
        
        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }
    
    public function getOnlineUsersApi(Request $request)
    {
        $onlineUsers = $this->getOnlineUsers();
        
        return response()->json([
            'success' => true,
            'data' => $onlineUsers
        ]);
    }

    public function getRecentArchivesApi(Request $request)
    {
        $user = auth()->user();
        $recentArchives = $this->getRecentArchives($user);
        
        return response()->json([
            'success' => true,
            'data' => $recentArchives
        ]);
    }

}