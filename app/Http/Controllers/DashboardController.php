<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;
    protected User $user;

    public function __construct(DashboardService $dashboardService, User $user)
    {
        $this->dashboardService = $dashboardService;
        $this->user = $user;
    }

    public function index()
    {
        $data = [
            'classess' => $this->dashboardService->mainDashboard(),
            'user' => $this->user->find(auth()->user()->id),
        ];
        return view('dashboard.index', $data);
    }

    public function attendance($id = '', $date = '')
    {
        $data = [
            'date' => $date,
            'id' => $id,
            'students' => $this->dashboardService->attendanceDashboard($id, $date),
            'user' => $this->user->find(auth()->user()->id),
        ];
        return view('dashboard.attendance', $data);
    }
}
