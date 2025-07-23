<?php

namespace App\Services\Quotes;

use App\Services\Cuadriculas\CuadriculaFactory;
use App\Services\Pergolas\PergolaFactory;
use Illuminate\Support\Facades\Log;

class QuotePDFGenerator
{
    private const TIPOS_CUADRICULA = ['cuadricula', 'cuadricula_trama'];

    public function generateAllPDFs(array $services, array $inputsPorServicio, $quotation = null): array
    {
        try {
            Log::info('=== INICIO GENERACIÓN TODOS LOS PDFs ===', [
                'services_count' => count($services),
                'inputs_count' => count($inputsPorServicio),
                'quotation_id' => $quotation?->id,
                'memory_usage' => memory_get_usage(true)
            ]);

            $pdfs_generados = [];

            // 1. Generar UN SOLO PDF comercial con todos los servicios
            if ($quotation) {
                Log::info('Generando PDF comercial...');
                $commercialPDF = $this->generateCommercialPDF($services, $inputsPorServicio, $quotation);
                $pdfs_generados[] = $commercialPDF;
                Log::info('PDF comercial generado:', $commercialPDF);
            } else {
                Log::warning('No se generará PDF comercial - quotation es null');
            }

            // 2. Generar PDFs de producción individuales para cada servicio
            Log::info('Generando PDFs de producción individuales...');
            foreach ($services as $index => $servicio) {
                Log::info("Generando PDFs para servicio {$index}:", $servicio);
                
                $productionPDFs = $this->generateProductionPDFsForService($servicio, $inputsPorServicio, $quotation);
                $pdfs_generados = array_merge($pdfs_generados, $productionPDFs);
                
                Log::info("PDFs generados para servicio {$index}:", $productionPDFs);
            }

            Log::info('=== FIN GENERACIÓN TODOS LOS PDFs ===', [
                'total_pdfs_generated' => count($pdfs_generados),
                'pdfs_generados' => $pdfs_generados,
                'memory_usage' => memory_get_usage(true)
            ]);

            return $pdfs_generados;
            
        } catch (\Exception $e) {
            Log::error('=== ERROR EN GENERACIÓN DE PDFs ===', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString(),
                'services' => $services,
                'inputs' => $inputsPorServicio,
                'quotation_id' => $quotation?->id
            ]);
            throw $e;
        }
    }

    /**
     * Genera UN SOLO PDF comercial con todos los servicios de la cotización
     */
    private function generateCommercialPDF(array $services, array $inputsPorServicio, $quotation): array
    {
        try {
            Log::info('=== GENERANDO PDF COMERCIAL ===', [
                'services_count' => count($services),
                'quotation_id' => $quotation->id
            ]);

            // Usar el primer servicio como base para el PDF comercial
            $firstService = $services[0];
            Log::info('Primer servicio para PDF comercial:', $firstService);
            
            $inputs = $inputsPorServicio[$firstService['input_index']];
            Log::info('Inputs del primer servicio:', $inputs);

            // Agregar información del cliente y cotización
            $inputs['quotation_id'] = 'COT-' . str_pad($quotation->id, 6, '0', STR_PAD_LEFT);
            $inputs['client_name'] = $quotation->client->name ?? '';
            $inputs['client_dni'] = $quotation->client->dni ?? '';
            $inputs['client_phone'] = $quotation->client->phone ?? '';
            $inputs['client_province'] = $quotation->client->province ?? '';
            $inputs['total'] = $quotation->total;
            $inputs['iva'] = $quotation->iva;
            $inputs['pvp'] = $quotation->pvp;

            Log::info('Información del cliente agregada:', [
                'quotation_id' => $inputs['quotation_id'],
                'client_name' => $inputs['client_name'],
                'total' => $inputs['total']
            ]);

            // Preparar información de TODOS los servicios para el PDF
            $inputs['all_services'] = [];
            
            foreach ($services as $index => $servicio) {
                Log::info("Procesando servicio {$index} para PDF comercial:", $servicio);
                
                $serviceInputs = $inputsPorServicio[$servicio['input_index']];
                
                $inputs['all_services'][] = [
                    'service_id' => $servicio['service_id'],
                    'variant_id' => $servicio['variant_id'] ?? null,
                    'selected_cuadricula' => $servicio['selected_cuadricula'] ?? null,
                    'color' => $servicio['color'] ?? $servicio['selected_color'] ?? 'azul',
                    'inputs' => $serviceInputs
                ];
            }

            Log::info('Todos los servicios preparados:', $inputs['all_services']);

            // Generar PDF comercial usando el primer servicio como base
            Log::info('Creando pérgola con PergolaFactory...');
            $pergola = PergolaFactory::crear($firstService['service_id'], $inputs);
            
            Log::info('Calculando pérgola...');
            $pergola->calcular();
            
            Log::info('Generando PDF de cotización...');
            $pdfPath = $pergola->obtenerPDFCotizacion();
            
            $result = [
                'titulo' => 'Cotización Comercial',
                'path' => $pdfPath,
            ];
            
            Log::info('PDF comercial generado exitosamente:', $result);
            return $result;
            
        } catch (\Exception $e) {
            Log::error('=== ERROR EN PDF COMERCIAL ===', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString(),
                'services' => $services,
                'quotation_id' => $quotation->id
            ]);
            throw $e;
        }
    }

        /**
     * Genera PDFs de producción para un servicio individual
     */
    private function generateProductionPDFsForService(array $servicio, array $inputsPorServicio, $quotation = null): array
    {
        try {
            Log::info('=== GENERANDO PDFs DE PRODUCCIÓN ===', [
                'servicio' => $servicio,
                'quotation_id' => $quotation?->id
            ]);

            $pdfs = [];
            $inputs = $inputsPorServicio[$servicio['input_index']];
            
            Log::info('Inputs del servicio:', $inputs);

            // Agregar información básica si hay cotización
            if ($quotation) {
                $inputs['quotation_id'] = 'COT-' . str_pad($quotation->id, 6, '0', STR_PAD_LEFT);
                $inputs['client_name'] = $quotation->client->name ?? '';
                Log::info('Información de quotation agregada:', [
                    'quotation_id' => $inputs['quotation_id'],
                    'client_name' => $inputs['client_name']
                ]);
            }

            // Agregar información del servicio específico
            $inputs['service_variant_id'] = $servicio['variant_id'] ?? null;
            $inputs['selected_cuadricula'] = $servicio['selected_cuadricula'] ?? null;

            Log::info('Información del servicio agregada:', [
                'service_variant_id' => $inputs['service_variant_id'],
                'selected_cuadricula' => $inputs['selected_cuadricula']
            ]);

            // Generar PDF de orden de producción de pérgola
            Log::info('Generando PDF de orden de producción de pérgola...');
            $pergola = PergolaFactory::crear($servicio['service_id'], $inputs);
            Log::info('Pérgola creada, calculando...');
            $pergola->calcular();
            Log::info('Pérgola calculada, generando PDF...');
            $pergolaPath = $pergola->obtenerPDFOrdenProduccion();
            
            $pergolaArrayData = [
                'titulo' => 'Orden Producción Pérgola',
                'path' => $pergolaPath,
            ];
            $pdfs[] = $pergolaArrayData;
            Log::info('PDF de pérgola generado:', $pergolaArrayData);

            // Generar PDF de cuadrícula si aplica
            if (in_array($servicio['selected_cuadricula'], self::TIPOS_CUADRICULA)) {
                Log::info('Generando PDF de cuadrícula para tipo:', $servicio['selected_cuadricula']);
                
                $cuadricula = CuadriculaFactory::crear($servicio['selected_cuadricula'], $inputs);
                Log::info('Cuadrícula creada, calculando...');
                $cuadricula->calcular();
                Log::info('Cuadrícula calculada, generando PDF...');
                $cuadriculaPath = $cuadricula->obtenerPDFOrdenProduccion();
                
                $cuadriculaArrayData = [
                    'titulo' => 'Orden Producción Cuadrícula',
                    'path' => $cuadriculaPath,
                ];
                $pdfs[] = $cuadriculaArrayData;
                Log::info('PDF de cuadrícula generado:', $cuadriculaArrayData);
            } else {
                Log::info('No se genera PDF de cuadrícula - tipo no compatible:', [
                    'selected_cuadricula' => $servicio['selected_cuadricula'],
                    'tipos_validos' => self::TIPOS_CUADRICULA
                ]);
            }

            Log::info('PDFs de producción generados exitosamente:', $pdfs);
            return $pdfs;
            
        } catch (\Exception $e) {
            Log::error('=== ERROR EN PDFs DE PRODUCCIÓN ===', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString(),
                'servicio' => $servicio,
                'quotation_id' => $quotation?->id
            ]);
            throw $e;
        }
    }
}
