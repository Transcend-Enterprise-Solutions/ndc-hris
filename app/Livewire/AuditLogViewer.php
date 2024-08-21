<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use OwenIt\Auditing\Models\Audit;
use App\Models\User;
use App\Models\DocRequest;
use App\Models\DTRSchedule;
use App\Models\Holiday;

class AuditLogViewer extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $dateFrom = '';
    public $dateTo = '';

    protected $queryString = ['search', 'sortField', 'sortDirection', 'dateFrom', 'dateTo'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        $audits = Audit::with('user')
            ->where(function ($query) use ($searchTerm) {
                $query->where('event', 'like', $searchTerm)
                      ->orWhere(function ($subQuery) use ($searchTerm) {
                          $subQuery->whereRaw("JSON_CONTAINS(LOWER(new_values), LOWER(?), '$')", [$this->search])
                                   ->orWhereRaw("JSON_CONTAINS(LOWER(old_values), LOWER(?), '$')", [$this->search]);
                      })
                      ->orWhereHas('user', function ($query) use ($searchTerm) {
                          $query->where('name', 'like', $searchTerm);
                      })
                      ->orWhereRaw("LOWER(CONCAT(
                          'User ',
                          COALESCE((SELECT name FROM users WHERE id = audits.user_id), 'System'),
                          ' ',
                          audits.event,
                          ' ',
                          CASE
                              WHEN audits.auditable_type = 'App\\Models\\DocRequest' THEN 'document request'
                              WHEN audits.auditable_type = 'App\\Models\\DTRSchedule' THEN 'schedule'
                              WHEN audits.auditable_type = 'App\\Models\\Holiday' THEN 'holiday'
                              ELSE audits.auditable_type
                          END,
                          ' (ID: ',
                          audits.auditable_id,
                          ').'
                      )) LIKE ?", [$searchTerm]);
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        foreach ($audits as $audit) {
            $audit->resolved_new_values = $this->resolveValues($audit, $audit->new_values);
            $audit->resolved_old_values = $this->resolveValues($audit, $audit->old_values);
        }

        return view('livewire.audit-log-viewer', [
            'audits' => $audits,
        ]);
    }

    private function resolveValues($audit, $values)
    {
        $resolved = $values;

        if ($audit->auditable_type === DocRequest::class) {
            if (isset($resolved['user_id'])) {
                $user = User::find($resolved['user_id']);
                $resolved['user_name'] = $user ? $user->name : 'Unknown User';
            }
        } elseif ($audit->auditable_type === DTRSchedule::class) {
            if (isset($resolved['emp_code'])) {
                $user = User::where('emp_code', $resolved['emp_code'])->first();
                $resolved['employee_name'] = $user ? $user->name : 'Unknown Employee';
            }
        }

        return $resolved;
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="flex w-full flex-col gap-2">
            <livewire:skeleton/>
        </div>
        HTML;
    }
}
