<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use OwenIt\Auditing\Models\Audit;

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
                          $query->where('name', 'like', $searchTerm)
                                ->orWhere('id', 'like', $searchTerm);
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

        return view('livewire.audit-log-viewer', [
            'audits' => $audits,
        ]);
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
