<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogEntry;
use App\Models\ProjectEntry;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isSupervisor()) {
            return redirect()->route('supervisor.dashboard');
        }
        
        // Calculate reflection statistics from both log entries and project entries
        $logReflections = $user->logEntries()->whereNotNull('weekly_reflection_content');
        $projectReflections = $user->projectEntries()->whereNotNull('weekly_reflection_content');
        
        $stats = [
            'total_entries' => $user->logEntries()->count(),
            'approved_entries' => $user->logEntries()->where('supervisor_approved', true)->count(),
            'pending_entries' => $user->logEntries()->where('supervisor_approved', false)->count(),
            'total_project_entries' => $user->projectEntries()->count(),
            'approved_project_entries' => $user->projectEntries()->where('supervisor_approved', true)->count(),
            'pending_project_entries' => $user->projectEntries()->where('supervisor_approved', false)->count(),
            'total_reflections' => $logReflections->count() + $projectReflections->count(),
            'signed_reflections' => $logReflections->where('reflection_supervisor_signed', true)->count() + 
                                   $projectReflections->where('reflection_supervisor_signed', true)->count(),
            'pending_reflections' => $logReflections->where('reflection_supervisor_signed', false)->count() + 
                                    $projectReflections->where('reflection_supervisor_signed', false)->count(),
        ];
        
        $recentEntries = $user->logEntries()->latest()->limit(5)->get();
        $recentProjectEntries = $user->projectEntries()->latest()->limit(5)->get();

        // Prepare last 30 days combined daily counts for a contribution-style chart
        $days = 30;
        $dailyCounts = collect();
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $logCount = $user->logEntries()->whereDate('date', Carbon::today()->subDays($i))->count();
            $projectCount = $user->projectEntries()->whereDate('date', Carbon::today()->subDays($i))->count();
            $dailyCounts[$date] = $logCount + $projectCount;
        }

        // Prepare 52-week contribution data (7 rows x 52 columns). Use grouped queries for performance.
        $end = Carbon::today();
        // start at the beginning of the week 51 weeks ago so we have 52 weeks total
        $start = $end->copy()->startOfWeek(Carbon::SUNDAY)->subWeeks(51);

        // Fetch grouped counts for logs and projects in one query each
        $logGrouped = $user->logEntries()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->selectRaw("DATE(`date`) as day, COUNT(*) as cnt")
            ->groupBy('day')
            ->pluck('cnt', 'day')
            ->toArray();

        $projGrouped = $user->projectEntries()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->selectRaw("DATE(`date`) as day, COUNT(*) as cnt")
            ->groupBy('day')
            ->pluck('cnt', 'day')
            ->toArray();

        // Build weeks array: each week is array of 7 day arrays ['date'=>..., 'count'=>...]
        $contribWeeks = [];
        $contribMax = 0;
        $cursor = $start->copy();
        for ($w = 0; $w < 52; $w++) {
            $week = [];
            for ($d = 0; $d < 7; $d++) {
                $dateStr = $cursor->toDateString();
                $count = (int) ( ($logGrouped[$dateStr] ?? 0) + ($projGrouped[$dateStr] ?? 0) );
                $week[] = ['date' => $dateStr, 'count' => $count];
                if ($count > $contribMax) $contribMax = $count;
                $cursor->addDay();
            }
            $contribWeeks[] = $week;
        }
        
        // Get recent reflections from both log entries and project entries
        $recentLogReflections = $user->logEntries()
            ->whereNotNull('weekly_reflection_content')
            ->latest()
            ->limit(2)
            ->get()
            ->map(function($entry) {
                return (object) [
                    'id' => $entry->id,
                    'type' => 'log',
                    'content' => $entry->weekly_reflection_content,
                    'week_start' => $entry->reflection_week_start,
                    'signed' => $entry->reflection_supervisor_signed,
                    'created_at' => $entry->created_at,
                    'date' => $entry->date
                ];
            });
            
        $recentProjectReflections = $user->projectEntries()
            ->whereNotNull('weekly_reflection_content')
            ->latest()
            ->limit(2)
            ->get()
            ->map(function($entry) {
                return (object) [
                    'id' => $entry->id,
                    'type' => 'project',
                    'content' => $entry->weekly_reflection_content,
                    'week_start' => $entry->reflection_week_start,
                    'signed' => $entry->reflection_supervisor_signed,
                    'created_at' => $entry->created_at,
                    'activity' => $entry->activity
                ];
            });
            
        $recentReflections = $recentLogReflections->concat($recentProjectReflections)
            ->sortByDesc('created_at')
            ->take(3);
        
        return view('home', compact('stats', 'recentEntries', 'recentProjectEntries', 'recentReflections', 'dailyCounts', 'contribWeeks', 'contribMax'));
    }

    /**
     * Return last 30 days daily counts as JSON for chart polling.
     */
    public function dailyCounts(Request $request)
    {
        $user = auth()->user();
        $days = 30;
        $dailyCounts = collect();
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $logCount = $user->logEntries()->whereDate('date', Carbon::today()->subDays($i))->count();
            $projectCount = $user->projectEntries()->whereDate('date', Carbon::today()->subDays($i))->count();
            $dailyCounts[$date] = $logCount + $projectCount;
        }

        return response()->json($dailyCounts);
    }
}
