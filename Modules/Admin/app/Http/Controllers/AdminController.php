<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Analytics\RevenueAnalyticsRepository;
use App\Repositories\Analytics\SalesAnalyticsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class AdminController extends Controller
{
    protected $revenueRepo;
    protected $salesRepo;

    public function __construct(
        RevenueAnalyticsRepository $revenueRepo,
        SalesAnalyticsRepository $salesRepo
    ) {
        $this->revenueRepo = $revenueRepo;
        $this->salesRepo = $salesRepo;
    }

    /**
     * Display admin dashboard with real analytics data
     */
    public function index()
    {
        // Get default date range (last 30 days)
        $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        // Fetch analytics data
        $data = [
            'revenue' => $this->revenueRepo->getRevenueStats($startDate, $endDate),
            'dailyRevenue' => $this->revenueRepo->getDailyRevenue(30),
            'topProducts' => $this->salesRepo->getTopSellingProducts(10, $startDate, $endDate),
            'categoryPerformance' => $this->salesRepo->getCategoryPerformance($startDate, $endDate),
            'lowStock' => $this->salesRepo->getLowStockProducts(10),
            'outOfStock' => $this->salesRepo->getOutOfStockProducts(),
            'salesTrends' => $this->salesRepo->getSalesTrends('daily', 30),
            'paymentMethods' => $this->salesRepo->getSalesByPaymentMethod($startDate, $endDate),
        ];

        // Add permission checks for role-based visibility
        $data['canViewFinancial'] = Gate::allows('view_financial_reports');
        $data['isAdmin'] = auth()->guard('admin')->user()->isAdmin();

        return view('admin::dashboard', compact('data'));
    }

    public function profile()
    {
        return view('admin::profile');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
    public function changePassword()
    {
        return view('admin::auth.passwords.change');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $admin->password)) {
            return back()->with('error', 'Current password does not match!');
        }

        $admin->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password successfully changed!');
    }
}
