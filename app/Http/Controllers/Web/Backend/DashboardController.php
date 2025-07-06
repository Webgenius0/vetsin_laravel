<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Faq;
use App\Models\DynamicPage;
use Illuminate\Support\Facades\DB;
use Shetabit\Visitor\Facade\Visitor;

class DashboardController extends Controller
{
    public function index() {
        // Track dashboard visit
        Visitor::visit();

        // Get total users
        $totalUsers = User::count();

        // Get total FAQs
        $totalFaqs = Faq::count();

        // Get total dynamic pages
        $totalDynamicPages = DynamicPage::count();

        // Get visits for the last 7 days
        $visits = DB::table(config('visitor.table_name', 'shetabit_visits'))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $visits->pluck('date')->map(fn($d) => date('Y-m-d', strtotime($d)))->toArray();
        $data = $visits->pluck('total')->map(fn($count) => (int)$count)->toArray();

        return view('backend.layouts.index', compact(
            'totalUsers',
            'totalFaqs',
            'totalDynamicPages',
            'labels',
            'data'
        ));
    }
}
