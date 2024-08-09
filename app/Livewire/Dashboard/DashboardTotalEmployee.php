<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;
class DashboardTotalEmployee extends Component
{
    public $totalEmployees;
    public $months;
    public $monthlyHires;

    public function mount()
    {
        $this->totalEmployees = User::where('user_role', 'emp')->count();

        $hiredByMonth = User::where('user_role', 'emp')
            ->groupBy(FacadesDB::raw('MONTH(created_at)'))
            ->orderBy(FacadesDB::raw('MONTH(created_at)'), 'asc')
            ->select(FacadesDB::raw('COUNT(*) as count'), FacadesDB::raw('MONTH(created_at) as month'))
            ->get();

        $this->months = $hiredByMonth->pluck('month')->map(function ($month) {
            return date('M', mktime(0, 0, 0, $month, 1));
        })->toArray();

        $this->monthlyHires = $hiredByMonth->pluck('count')->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-total-employee');
    }
}
