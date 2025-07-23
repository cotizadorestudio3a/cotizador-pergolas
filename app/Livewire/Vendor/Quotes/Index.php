<?php

namespace App\Livewire\Vendor\Quotes;

use App\Models\Services;
use App\Models\ServiceVariants;
use App\Services\Quotes\QuoteCalculator;
use App\Services\Quotes\QuoteInputValidator;
use App\Services\Quotes\QuotePDFGenerator;
use App\Services\Quotes\QuoteServiceManager;
use App\Services\Quotes\ColumnColorManager;
use App\Services\Quotes\QuotationService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
    
    // Cotización guardada
    public $saved_quotation_id = null;
    
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
    private QuotationService $quotationService;

    public function boot()
    {
        $this->calculator = app(QuoteCalculator::class);
        $this->validator = app(QuoteInputValidator::class);
        $this->pdfGenerator = app(QuotePDFGenerator::class);
        $this->serviceManager = app(QuoteServiceManager::class);
        $this->colorManager = app(ColumnColorManager::class);
        $this->quotationService = app(QuotationService::class);
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

    /**
     * Elimina un servicio específico de la lista
     */
    public function removeService(int $serviceIndex): void
    {
        if (!isset($this->added_services[$serviceIndex])) {
            return;
        }

        // Eliminar el servicio y sus inputs
        unset($this->added_services[$serviceIndex]);
        unset($this->inputsPorServicio[$serviceIndex]);

        // Reindexar los arrays para mantener índices consecutivos
        $this->added_services = array_values($this->added_services);
        $this->inputsPorServicio = array_values($this->inputsPorServicio);

        // Ajustar el activeServiceIndex si es necesario
        if ($this->activeServiceIndex >= count($this->added_services)) {
            $this->activeServiceIndex = max(0, count($this->added_services) - 1);
        }

        // Resetear totales después de eliminar
        $this->pvp = 0;
        $this->iva = 0;
        $this->total = 0;
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
            // Debug completo del estado antes de empezar
            $this->debugPrePDFGeneration();

            Log::info('=== INICIO GENERACIÓN PDF ===', [
                'user_id' => Auth::id(),
                'client_id' => $this->client_id,
                'step' => $this->step,
                'added_services_count' => count($this->added_services),
                'inputs_count' => count($this->inputsPorServicio),
                'total' => $this->total,
                'memory_usage' => memory_get_usage(true),
                'time' => now()->toDateTimeString()
            ]);

            // Validar que tengamos toda la información necesaria
            Log::info('Iniciando validación de datos...');
            $validationErrors = $this->validateQuotationData();

            if (!empty($validationErrors)) {
                Log::error('Errores de validación encontrados:', $validationErrors);
                foreach ($validationErrors as $field => $message) {
                    $this->addError($field, $message);
                }
                return;
            }
            Log::info('Validación de datos completada exitosamente');
            
            // Guardar la cotización en la base de datos
            Log::info('Iniciando guardado de cotización en BD...');
            $quotation = $this->saveQuotationToDatabase();
            Log::info('Cotización guardada exitosamente:', [
                'quotation_id' => $quotation->id,
                'memory_usage' => memory_get_usage(true)
            ]);
        
            // Generar los PDFs
            Log::info('Iniciando generación de PDFs...', [
                'added_services' => $this->added_services,
                'inputs_por_servicio' => $this->inputsPorServicio,
                'quotation_id' => $quotation->id
            ]);

            $this->pdfs_generados = $this->pdfGenerator->generateAllPDFs(
                $this->added_services, 
                $this->inputsPorServicio,
                $quotation
            );

            Log::info('PDFs generados exitosamente:', [
                'pdfs_count' => count($this->pdfs_generados),
                'pdfs_generados' => $this->pdfs_generados,
                'memory_usage' => memory_get_usage(true)
            ]);

            Log::info('Cambiando step a 4...');
            $this->step = 4;
            Log::info('Step cambiado exitosamente, step actual:', ['step' => $this->step]);
            
            // Dispatch evento de cotización guardada exitosamente
            Log::info('Disparando evento cotizacionGuardada...');
            $this->dispatch('cotizacionGuardada', [
                'quotation_id' => $quotation->id,
                'message' => 'Cotización guardada exitosamente'
            ]);
            Log::info('Evento cotizacionGuardada disparado exitosamente');
            
            Log::info('=== FIN GENERACIÓN PDF EXITOSA ===', [
                'final_step' => $this->step,
                'final_memory_usage' => memory_get_usage(true),
                'time' => now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('=== ERROR EN GENERACIÓN PDF ===', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'client_id' => $this->client_id,
                'step' => $this->step,
                'memory_usage' => memory_get_usage(true),
                'time' => now()->toDateTimeString()
            ]);
            
            $this->addError('pdf_generation', 'Error al procesar la cotización: ' . $e->getMessage());
        } catch (\Throwable $t) {
            Log::error('=== ERROR FATAL EN GENERACIÓN PDF ===', [
                'error_message' => $t->getMessage(),
                'error_file' => $t->getFile(),
                'error_line' => $t->getLine(),
                'error_trace' => $t->getTraceAsString(),
                'user_id' => Auth::id(),
                'client_id' => $this->client_id,
                'step' => $this->step,
                'memory_usage' => memory_get_usage(true),
                'time' => now()->toDateTimeString()
            ]);
            
            $this->addError('pdf_generation', 'Error fatal al procesar la cotización: ' . $t->getMessage());
        }
    }

    /**
     * Valida los datos necesarios para crear una cotización
     */
    private function validateQuotationData(): array
    {
        $errors = [];

        if (empty($this->client_id)) {
            $errors['client_id'] = 'Debe seleccionar un cliente';
        }

        if (empty($this->added_services)) {
            $errors['services'] = 'Debe agregar al menos un servicio';
        }

        if ($this->total <= 0) {
            $errors['total'] = 'Debe calcular el total antes de finalizar';
        }

        return $errors;
    }

    /**
     * Guarda la cotización en la base de datos
     */
    private function saveQuotationToDatabase()
    {
        try {
            Log::info('=== GUARDANDO COTIZACIÓN EN BD ===');
            
            $quotationData = [
                'client_id' => $this->client_id,
                'user_id' => Auth::id(),
                'total' => $this->total,
                'iva' => $this->iva,
                'pvp' => $this->pvp,
            ];

            Log::info('Datos de cotización preparados:', $quotationData);
            Log::info('Servicios agregados:', $this->added_services);
            Log::info('Inputs por servicio:', $this->inputsPorServicio);

            $quotation = $this->quotationService->createQuotation(
                $quotationData,
                $this->added_services,
                $this->inputsPorServicio
            );

            Log::info('Cotización creada exitosamente:', [
                'quotation_id' => $quotation->id,
                'quotation_data' => $quotation->toArray()
            ]);

            $this->saved_quotation_id = $quotation->id;
            Log::info('Saved quotation ID asignado:', ['saved_quotation_id' => $this->saved_quotation_id]);

            return $quotation;
            
        } catch (\Exception $e) {
            Log::error('Error al guardar cotización:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
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
    // MÉTODOS DE GESTIÓN DE COTIZACIONES GUARDADAS
    // ==========================================

    /**
     * Obtiene las cotizaciones del usuario actual
     */
    public function getUserQuotations()
    {
        return $this->quotationService->getUserQuotations(Auth::id());
    }

    /**
     * Obtiene las cotizaciones de un cliente específico
     */
    public function getClientQuotations(int $clientId)
    {
        return $this->quotationService->getClientQuotations($clientId);
    }

    /**
     * Reinicia el formulario para crear una nueva cotización
     */
    public function startNewQuotation()
    {
        $this->reset([
            'step',
            'selectedService',
            'selectedColor',
            'selectedVariant', 
            'selectedCuadricula',
            'client_id',
            'added_services',
            'inputsPorServicio',
            'pvp',
            'iva',
            'total',
            'pdfs_generados',
            'saved_quotation_id'
        ]);
        
        $this->step = 1;
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
        // Debug del estado del componente en cada render
        Log::info('=== RENDER DEL COMPONENTE ===', [
            'step' => $this->step,
            'user_id' => Auth::id(),
            'pdfs_generados_count' => count($this->pdfs_generados),
            'saved_quotation_id' => $this->saved_quotation_id,
            'memory_usage' => memory_get_usage(true),
            'time' => now()->toDateTimeString()
        ]);

        $services = Services::all();
        $variants = $this->variants;
        $clients = Auth::user()?->clients ?? collect();
        $added_services = $this->added_services;
        
        return view('livewire.vendor.quotes.index', compact('services', 'variants', 'clients', 'added_services'));
    }

    // ==========================================
    // MÉTODOS DE DEBUG (TEMPORALES)
    // ==========================================

    /**
     * Método temporal de debug para verificar datos
     */
    public function debugInputs()
    {
        Log::info('Debug serviceCosts:', $this->serviceCosts);
        Log::info('Debug added_services:', $this->added_services);
        Log::info('Debug inputsPorServicio:', $this->inputsPorServicio);
        Log::info('Debug total values:', [
            'pvp' => $this->pvp,
            'iva' => $this->iva,
            'total' => $this->total
        ]);
    }

    /**
     * Debug completo antes de generar PDFs
     */
    public function debugPrePDFGeneration()
    {
        Log::info('=== DEBUG PRE-GENERACIÓN PDF ===', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'client_id' => $this->client_id,
            'step' => $this->step,
            'added_services' => $this->added_services,
            'inputsPorServicio' => $this->inputsPorServicio,
            'pvp' => $this->pvp,
            'iva' => $this->iva,
            'total' => $this->total,
            'pdfs_generados' => $this->pdfs_generados,
            'saved_quotation_id' => $this->saved_quotation_id,
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            'time_limit' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version()
        ]);

        // Verificar que los servicios están completos
        foreach ($this->added_services as $index => $service) {
            Log::info("Servicio {$index} completo:", [
                'service_data' => $service,
                'has_inputs' => isset($this->inputsPorServicio[$service['input_index']]),
                'inputs_data' => $this->inputsPorServicio[$service['input_index']] ?? 'NO ENCONTRADO'
            ]);
        }
    }

    /**
     * Método para verificar el estado actual del componente (usar desde navegador)
     */
    public function debugCurrentState()
    {
        $state = [
            'step' => $this->step,
            'user_id' => Auth::id(),
            'client_id' => $this->client_id,
            'added_services' => $this->added_services,
            'inputsPorServicio' => $this->inputsPorServicio,
            'pvp' => $this->pvp,
            'iva' => $this->iva,
            'total' => $this->total,
            'pdfs_generados' => $this->pdfs_generados,
            'saved_quotation_id' => $this->saved_quotation_id,
            'memory_usage' => memory_get_usage(true),
            'time' => now()->toDateTimeString()
        ];

        Log::info('=== DEBUG ESTADO ACTUAL ===', $state);
        
        // También mostrar en la consola del navegador
        $this->dispatch('console-log', $state);
        
        return $state;
    }

}
