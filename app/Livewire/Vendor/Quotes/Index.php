<?php

namespace App\Livewire\Vendor\Quotes;

use App\Models\Services;
use App\Models\ServiceVariants;
use App\Services\Quotes\QuoteCalculator;
use App\Services\Quotes\QuoteInputValidator;
use App\Services\Quotes\QuotePDFGenerator;
use App\Services\Quotes\QuoteServiceManager;
use App\Services\Quotes\ColumnColorManager;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    // Constantes
    private const TIPOS_CUADRICULA = ['cuadricula', 'cuadricula_trama'];
    
    // Estados de navegación
    public int $step = 1;
    public int $newServiceStep = 1;
    public int $activeServiceIndex = 0;
    
    // Selecciones actuales
    public ?int $selectedService = 1;
    public ?string $selectedColor;
    public ?int $selectedVariant = 1;
    public ?string $selectedCuadricula;
    
    // Datos del cliente
    public $client_id;
    
    // Servicios y variantes disponibles
    public $available_services;
    public $available_variants;
    public $variants;
    
    // Servicios agregados y sus inputs
    public array $added_services = [];
    public array $inputsPorServicio = [];
    
    // Cálculos financieros
    public $pvp = 0;
    public $iva = 0;
    public $total = 0;
    
    // PDFs generados
    public array $pdfs_generados = [];
    
    // Selector de color de columnas
    public bool $selectorColorVisible = false;
    public ?int $servicioSelectorColor = null;
    public ?int $indiceSelectorColor = null;

    // Servicios inyectados
    private QuoteCalculator $calculator;
    private QuoteInputValidator $validator;
    private QuotePDFGenerator $pdfGenerator;
    private QuoteServiceManager $serviceManager;
    private ColumnColorManager $colorManager;

    public function boot()
    {
        $this->calculator = app(QuoteCalculator::class);
        $this->validator = app(QuoteInputValidator::class);
        $this->pdfGenerator = app(QuotePDFGenerator::class);
        $this->serviceManager = app(QuoteServiceManager::class);
        $this->colorManager = app(ColumnColorManager::class);
    }

    // ==========================================
    // MÉTODOS DE NAVEGACIÓN
    // ==========================================
    
    public function irPasoSiguiente()
    {
        match ($this->step) {
            1 => $this->procesarPaso1(),
            2 => $this->procesarPaso2(),
            default => null
        };
    }
    
    private function procesarPaso1(): void
    {
        $this->validateColorSelection();
        $this->loadServiceVariants();
        $this->step = 2;
    }
    
    private function procesarPaso2(): void
    {
        $this->validateVariantAndGridSelection();
        $this->addServiceToList();
        $this->step = 3;
        $this->dispatch('irPasoSiguiente', $this->added_services);
    }
    
    private function validateColorSelection(): void
    {
        $this->validate([
            'selectedColor' => 'required'
        ], [
            'selectedColor' => 'Por favor, seleccione un color.'
        ]);
    }
    
    private function loadServiceVariants(): void
    {
        $this->variants = ServiceVariants::where('service_id', $this->selectedService)->get();
    }
    
    private function validateVariantAndGridSelection(): void
    {
        $this->validate([
            'selectedVariant' => 'required',
            'selectedCuadricula' => 'required'
        ], [
            'selectedVariant' => 'Por favor, seleccione una variante.',
            'selectedCuadricula' => 'Por favor, seleccione una cuadrícula.'
        ]);
    }
    
    private function addServiceToList(): void
    {
        $this->activeServiceIndex = $this->serviceManager->addServiceToList(
            $this->added_services,
            $this->inputsPorServicio,
            $this->selectedService,
            $this->selectedColor,
            $this->selectedVariant,
            $this->selectedCuadricula
        );
    }

    // ==========================================
    // MÉTODOS DE CÁLCULOS
    // ==========================================
    
    public function calcularTotal()
    {
        // Validar requisitos usando el servicio
        $validationErrors = $this->calculator->validateCalculationRequirements($this->client_id, $this->added_services);
        
        if (!empty($validationErrors)) {
            foreach ($validationErrors as $field => $message) {
                $this->addError($field, $message);
            }
            return;
        }
        
        // Limpiar errores previos
        $this->resetErrorBag();
        
        // Validar todos los inputs de servicios usando el servicio
        $allErrors = $this->validator->validateAllServices($this->added_services, $this->inputsPorServicio);
        
        if (!empty($allErrors)) {
            foreach ($allErrors as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }
            return;
        }
        
        try {
            // Calcular totales usando el servicio
            $totals = $this->calculator->calculateTotal($this->added_services, $this->inputsPorServicio);
            
            $this->pvp = $totals['pvp'];
            $this->iva = $totals['iva'];
            $this->total = $totals['total'];
            
            // Dispatch evento de cálculo exitoso
            $this->dispatch('calculoCompletado', [
                'pvp' => $this->pvp,
                'iva' => $this->iva,
                'total' => $this->total
            ]);
            
        } catch (\Exception $e) {
            $this->addError('calculo', 'Error al calcular el total: ' . $e->getMessage());
        }
    }


    // ==========================================
    // MÉTODOS DE GENERACIÓN DE PDF
    // ==========================================

    public function generatePDFFiles()
    {
        try {
            $this->pdfs_generados = $this->pdfGenerator->generateAllPDFs(
                $this->added_services, 
                $this->inputsPorServicio
            );

            $this->step = 4;
        } catch (\Exception $e) {
            $this->addError('pdf_generation', 'Error al generar PDFs: ' . $e->getMessage());
        }
    }

    public function decrementStep()
    {
        if ($this->step > 1) {
            $this->step--;
            $this->added_services = [];
            $this->reset('selectedCuadricula');
        }
    }

    // ==========================================
    // MÉTODOS DE GESTIÓN DE SERVICIOS
    // ==========================================

    /**
     * Reinicia el flujo para agregar un nuevo servicio manteniendo los que ya fueron agregados.
     */
    public function startAddService()
    {
        $this->newServiceStep = 1;
        $this->resetServiceSelection();
    }

    /**
     * Avanza al siguiente paso dentro del modal de nuevo servicio.
     */
    public function newServiceNextStep()
    {
        if ($this->newServiceStep === 1) {
            $this->validateServiceAndColor();
            $this->loadServiceVariants();
            $this->newServiceStep = 2;
        }
    }

    /**
     * Confirma la selección en el modal y agrega el servicio a la lista.
     */
    public function confirmAddService()
    {
        $this->validateVariantAndGridSelection();
        
        $this->activeServiceIndex = $this->serviceManager->addServiceToList(
            $this->added_services,
            $this->inputsPorServicio,
            $this->selectedService,
            $this->selectedColor,
            $this->selectedVariant,
            $this->selectedCuadricula
        );

        $this->closeAddServiceModal();
    }
    
    private function resetServiceSelection(): void
    {
        $this->reset(
            'selectedService',
            'selectedColor',
            'selectedVariant',
            'selectedCuadricula',
            'variants'
        );
    }
    
    private function validateServiceAndColor(): void
    {
        $this->validate([
            'selectedColor' => 'required',
            'selectedService' => 'required'
        ], [
            'selectedColor.required' => 'Seleccione un color',
            'selectedService.required' => 'Seleccione un servicio'
        ]);
    }
    
    private function closeAddServiceModal(): void
    {
        $this->dispatch('modal-close', name: 'add-service-modal');
        $this->newServiceStep = 1;
    }

    public function resetFormularioServicioNuevo()
    {
        $this->inputsPorServicio[] = $this->serviceManager->initializeServiceInputs();
    }

    // ==========================================
    // MÉTODOS DE GESTIÓN DE COLORES DE COLUMNAS
    // ==========================================
    
    public function abrirSelectorColorColumna($servicioIndex, $columnaIndex)
    {
        $this->servicioSelectorColor = $servicioIndex;
        $this->indiceSelectorColor = $columnaIndex;
        $this->selectorColorVisible = true;
    }

    public function cerrarSelectorColorColumna()
    {
        $this->selectorColorVisible = false;
        $this->servicioSelectorColor = null;
        $this->indiceSelectorColor = null;
    }

    public function cambiarColorColumna($color)
    {
        if ($this->servicioSelectorColor !== null && $this->indiceSelectorColor !== null) {
            $this->colorManager->changeColumnColor(
                $this->inputsPorServicio,
                $this->servicioSelectorColor,
                $this->indiceSelectorColor,
                $color
            );
        }
        
        $this->cerrarSelectorColorColumna();
    }
    
    public function updatedInputsPorServicio($value, $key)
    {
        if (str_contains($key, '.n_columnas')) {
            $this->adjustColumnColors($key, (int)$value);
        }
    }
    
    private function adjustColumnColors(string $key, int $numColumnas): void
    {
        $servicioIndex = explode('.', $key)[0];
        $defaultColor = $this->selectedColor ?? 'azul';
        
        $this->colorManager->adjustColumnColors(
            $this->inputsPorServicio,
            (int)$servicioIndex,
            $numColumnas,
            $defaultColor
        );
    }

    // ==========================================
    // MÉTODOS DE INICIALIZACIÓN Y RENDERIZADO
    // ==========================================
    
    public function mount()
    {
        $this->available_services = Services::all(['id', 'name']);
        // Cargar todas las variantes para el componente
        $this->available_variants = ServiceVariants::all(['id', 'name', 'service_id']);
    }

    public function render()
    {
        $services = Services::all();
        $variants = $this->variants;
        $clients = Auth::user()?->clients ?? collect();
        $added_services = $this->added_services;
        
        return view('livewire.vendor.quotes.index', compact('services', 'variants', 'clients', 'added_services'));
    }
}
