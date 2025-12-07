<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminDashboardService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AdminDashboardService $adminDashboardService
    ) {}

    public function __invoke(): View
    {
        $data = $this->adminDashboardService->getDashboardDataForAdmin(Auth::user());

        return view('admin.dashboard', $data);
    }
}
