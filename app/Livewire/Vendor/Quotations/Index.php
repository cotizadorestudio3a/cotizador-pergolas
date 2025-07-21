<?php

namespace App\Livewire\Vendor\Quotations;

use App\Models\Quotation;
use App\Services\Quotes\QuotationService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    // Filtros
    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    public ?int $selectedClientFilter = null;

    // Paginación
    protected string $paginationTheme = 'tailwind';

    // Servicios inyectados
    private QuotationService $quotationService;

    public function boot()
    {
        $this->quotationService = app(QuotationService::class);
    }

    /**
     * Resetea la paginación cuando se cambia la búsqueda
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Resetea la paginación cuando se cambia el filtro de cliente
     */
    public function updatingSelectedClientFilter()
    {
        $this->resetPage();
    }

    /**
     * Ordena las cotizaciones por el campo especificado
     */
    public function sortBy(string $field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Limpia todos los filtros
     */
    public function clearFilters()
    {
        $this->reset(['search', 'selectedClientFilter']);
        $this->resetPage();
    }

    /**
     * Elimina una cotización (soft delete)
     */
    public function deleteQuotation(int $quotationId)
    {
        try {
            $quotation = Quotation::where('user_id', Auth::id())
                ->findOrFail($quotationId);
            
            $quotation->delete();
            
            session()->flash('message', 'Cotización eliminada exitosamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar la cotización: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene las cotizaciones paginadas con filtros aplicados
     */
    private function getQuotations()
    {
        $query = Quotation::with(['client', 'quotationItems.service', 'quotationItems.serviceVariant'])
            ->where('user_id', Auth::id());

        // Filtro por búsqueda (busca en nombre del cliente)
        if (!empty($this->search)) {
            $query->whereHas('client', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('dni', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por cliente específico
        if ($this->selectedClientFilter) {
            $query->where('client_id', $this->selectedClientFilter);
        }

        // Ordenamiento
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate(10);
    }

    /**
     * Obtiene los clientes del usuario para el filtro
     */
    private function getClientsForFilter()
    {
        return Auth::user()->clients()
            ->select('clients.id', 'clients.name')
            ->orderBy('clients.name')
            ->get();
    }

    /**
     * Calcula estadísticas básicas
     */
    private function getStatistics()
    {
        $userId = Auth::id();
        
        return [
            'total_quotations' => Quotation::where('user_id', $userId)->count(),
            'total_amount' => Quotation::where('user_id', $userId)->sum('total'),
            'this_month' => Quotation::where('user_id', $userId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    }

    public function render()
    {
        $quotations = $this->getQuotations();
        $clients = $this->getClientsForFilter();
        $statistics = $this->getStatistics();
        
        return view('livewire.vendor.quotations.index', [
            'quotations' => $quotations,
            'clients' => $clients,
            'statistics' => $statistics,
        ]);
    }
}
