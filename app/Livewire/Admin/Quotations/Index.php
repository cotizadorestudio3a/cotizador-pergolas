<?php

namespace App\Livewire\Admin\Quotations;

use App\Models\Quotation;
use App\Models\User;
use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class Index extends Component
{
    use WithPagination;

    // Filtros de búsqueda
    public string $search = '';
    public string $vendorFilter = '';
    public string $clientFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

    // Estado de acciones
    public $selectedQuotation = null;
    public bool $showQuotationDetails = false;

    // ==========================================
    // MÉTODOS DE FILTRADO Y BÚSQUEDA
    // ==========================================

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedVendorFilter()
    {
        $this->resetPage();
    }

    public function updatedClientFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset([
            'search',
            'vendorFilter',
            'clientFilter',
            'dateFrom',
            'dateTo'
        ]);
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    // ==========================================
    // MÉTODOS DE VISUALIZACIÓN
    // ==========================================

    public function showDetails($quotationId)
    {
        $this->selectedQuotation = Quotation::with(['client', 'user', 'items.service'])
            ->findOrFail($quotationId);
        $this->showQuotationDetails = true;
    }

    public function closeDetails()
    {
        $this->showQuotationDetails = false;
        $this->selectedQuotation = null;
    }

    // ==========================================
    // MÉTODOS DE ACCIONES
    // ==========================================

    public function deleteQuotation($quotationId)
    {
        try {
            $quotation = Quotation::findOrFail($quotationId);
            
            // Eliminar items relacionados
            $quotation->items()->delete();
            
            // Eliminar la cotización
            $quotation->delete();

            session()->flash('success', 'Cotización eliminada correctamente');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la cotización: ' . $e->getMessage());
        }
    }

    // ==========================================
    // MÉTODOS DE CONSULTA
    // ==========================================

    public function getQuotationsProperty()
    {
        return Quotation::query()
            ->with(['client', 'user', 'items.service'])
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $subQuery) {
                    $subQuery->where('id', 'like', '%' . $this->search . '%')
                        ->orWhereHas('client', function (Builder $clientQuery) {
                            $clientQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('dni', 'like', '%' . $this->search . '%')
                                ->orWhere('phone', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('user', function (Builder $userQuery) {
                            $userQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->vendorFilter, function (Builder $query) {
                $query->where('user_id', $this->vendorFilter);
            })
            ->when($this->clientFilter, function (Builder $query) {
                $query->where('client_id', $this->clientFilter);
            })
            ->when($this->dateFrom, function (Builder $query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function (Builder $query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);
    }

    public function getVendorsProperty()
    {
        return User::whereHas('role', function ($query) {
            $query->where('name', 'vendedor');
        })->orderBy('name')->get(['id', 'name']);
    }

    public function getClientsProperty()
    {
        return Client::orderBy('name')->get(['id', 'name']);
    }

    // ==========================================
    // RENDERIZADO
    // ==========================================

    public function render()
    {
        return view('livewire.admin.quotations.index', [
            'quotations' => $this->quotations,
            'vendors' => $this->vendors,
            'clients' => $this->clients,
        ]);
    }
}
