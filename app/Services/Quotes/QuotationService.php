<?php

namespace App\Services\Quotes;

use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuotationService
{
    /**
     * Crea una nueva cotización con sus items
     */
    public function createQuotation(array $quotationData, array $services, array $inputsPorServicio): Quotation
    {
        return DB::transaction(function () use ($quotationData, $services, $inputsPorServicio) {
            // Crear la cotización principal
            $quotation = Quotation::create([
                'client_id' => $quotationData['client_id'],
                'user_id' => $quotationData['user_id'],
                'total' => $quotationData['total'],
                'iva' => $quotationData['iva'],
                'pvp' => $quotationData['pvp'],
            ]);

            // Crear los items de la cotización
            foreach ($services as $index => $service) {
                $this->createQuotationItem($quotation, $service, $inputsPorServicio[$index] ?? []);
            }

            Log::info('Cotización creada exitosamente', [
                'quotation_id' => $quotation->id,
                'client_id' => $quotation->client_id,
                'total_items' => count($services)
            ]);

            return $quotation->load('quotationItems');
        });
    }

    /**
     * Crea un item individual de cotización
     */
    private function createQuotationItem(Quotation $quotation, array $service, array $inputs): QuotationItem
    {
        // Calcular el precio para este item específico usando los inputs
        $calculatedPrice = $this->calculateItemPrice($service, $inputs);

        return QuotationItem::create([
            'quotation_id' => $quotation->id,
            'service_id' => $service['service_id'],
            'service_variant_id' => $service['variant_id'],
            'calculated_price' => $calculatedPrice,
        ]);
    }

    /**
     * Calcula el precio de un item específico basado en sus inputs
     */
    private function calculateItemPrice(array $service, array $inputs): float
    {
        // Aquí implementarías la lógica específica de cálculo por servicio
        // Por ahora, retornamos un valor base que puedes ajustar según tu lógica de negocio

        $basePrice = 100; // Precio base

        // Ejemplo de cálculo basado en inputs comunes
        if (isset($inputs['ancho'], $inputs['largo'])) {
            $area = (float) $inputs['ancho'] * (float) $inputs['largo'];
            $basePrice *= $area;
        }

        if (isset($inputs['n_columnas'])) {
            $basePrice += (int) $inputs['n_columnas'] * 50; // 50 por columna adicional
        }

        return round($basePrice, 2);
    }

    /**
     * Valida que los datos necesarios estén presentes
     */
    public function validateQuotationData(array $data): array
    {
        $errors = [];

        if (empty($data['client_id'])) {
            $errors['client_id'] = 'El cliente es requerido';
        }

        if (empty($data['user_id'])) {
            $errors['user_id'] = 'El usuario es requerido';
        }

        if (!isset($data['total']) || $data['total'] <= 0) {
            $errors['total'] = 'El total debe ser mayor a 0';
        }

        if (!isset($data['iva']) || $data['iva'] < 0) {
            $errors['iva'] = 'El IVA debe ser mayor o igual a 0';
        }

        if (!isset($data['pvp']) || $data['pvp'] <= 0) {
            $errors['pvp'] = 'El PVP debe ser mayor a 0';
        }

        return $errors;
    }

    /**
     * Obtiene todas las cotizaciones de un cliente
     */
    public function getClientQuotations(int $clientId): \Illuminate\Database\Eloquent\Collection
    {
        return Quotation::with(['quotationItems.service', 'quotationItems.serviceVariant', 'user'])
            ->where('client_id', $clientId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtiene todas las cotizaciones de un usuario
     */
    public function getUserQuotations(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return Quotation::with(['quotationItems.service', 'quotationItems.serviceVariant', 'client'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtiene una cotización específica con sus relaciones
     */
    public function getQuotationDetails(int $quotationId, int $userId): ?Quotation
    {
        return Quotation::with([
            'client',
            'quotationItems.service',
            'quotationItems.serviceVariant'
        ])
        ->where('user_id', $userId)
        ->find($quotationId);
    }

    /**
     * Obtiene estadísticas de cotizaciones para un usuario
     */
    public function getUserQuotationStats(int $userId): array
    {
        $quotations = Quotation::where('user_id', $userId);
        
        return [
            'total_count' => $quotations->count(),
            'total_amount' => $quotations->sum('total'),
            'average_amount' => $quotations->avg('total'),
            'this_month_count' => $quotations->whereMonth('created_at', now()->month)
                                            ->whereYear('created_at', now()->year)
                                            ->count(),
            'this_year_count' => $quotations->whereYear('created_at', now()->year)->count(),
        ];
    }
}
