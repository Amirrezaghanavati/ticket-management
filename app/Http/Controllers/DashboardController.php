<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService) {}

    public function __invoke(): View
    {
        $data = $this->dashboardService->getDashboardDataForUser(Auth::user());

        return view('dashboard', $data);
    }
}
