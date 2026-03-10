<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\STBC4866Entry;
use App\Models\STBC4966Entry;
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
        $logReflections = $user->STBC4866Entries()->whereNotNull('weekly_reflection_content');
        $projectReflections = $user->STBC4966Entries()->whereNotNull('weekly_reflection_content');
        
        $stats = [
            'total_entries' => $user->STBC4866Entries()->count(),
            'approved_entries' => $user->STBC4866Entries()->where('supervisor_approved', true)->count(),
            'pending_entries' => $user->STBC4866Entries()->where('supervisor_approved', false)->count(),
            'total_stbc4966_entries' => $user->STBC4966Entries()->count(),
            'approved_stbc4966_entries' => $user->STBC4966Entries()->where('supervisor_approved', true)->count(),
            'pending_stbc4966_entries' => $user->STBC4966Entries()->where('supervisor_approved', false)->count(),
            'total_reflections' => $logReflections->count() + $projectReflections->count(),
            'signed_reflections' => $logReflections->where('reflection_supervisor_signed', true)->count() + 
                                   $projectReflections->where('reflection_supervisor_signed', true)->count(),
            'pending_reflections' => $logReflections->where('reflection_supervisor_signed', false)->count() + 
                                    $projectReflections->where('reflection_supervisor_signed', false)->count(),
        ];
        
        $recentEntries = $user->STBC4866Entries()->latest()->limit(5)->get();
        $recentSTBC4966Entries = $user->STBC4966Entries()->latest()->limit(5)->get();

        // Prepare last 30 days combined daily counts for a contribution-style chart
        $days = 30;
        $dailyCounts = collect();
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $logCount = $user->STBC4866Entries()->whereDate('date', Carbon::today()->subDays($i))->count();
            $projectCount = $user->STBC4966Entries()->whereDate('date', Carbon::today()->subDays($i))->count();
            $dailyCounts[$date] = $logCount + $projectCount;
        }

        // Prepare 52-week contribution data (7 rows x 52 columns). Use grouped queries for performance.
        $end = Carbon::today();
        // start at the beginning of the week 51 weeks ago so we have 52 weeks total
        $start = $end->copy()->startOfWeek(Carbon::SUNDAY)->subWeeks(51);

        // Fetch grouped counts for logs and projects in one query each
        $logGrouped = $user->STBC4866Entries()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->selectRaw("DATE(`date`) as day, COUNT(*) as cnt")
            ->groupBy('day')
            ->pluck('cnt', 'day')
            ->toArray();

        $projGrouped = $user->STBC4966Entries()
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
        $recentLogReflections = $user->STBC4866Entries()
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
            
        $recentProjectReflections = $user->STBC4966Entries()
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
        
        return view('home', compact('stats', 'recentEntries', 'recentSTBC4966Entries', 'recentReflections', 'dailyCounts', 'contribWeeks', 'contribMax'));
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
            $logCount = $user->STBC4866Entries()->whereDate('date', Carbon::today()->subDays($i))->count();
            $projectCount = $user->STBC4966Entries()->whereDate('date', Carbon::today()->subDays($i))->count();
            $dailyCounts[$date] = $logCount + $projectCount;
        }

        return response()->json($dailyCounts);
    }
}
