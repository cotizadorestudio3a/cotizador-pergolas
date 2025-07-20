<?php

namespace App\Services\Quotes;

use Illuminate\Validation\ValidationException;

class QuoteInputValidator
{
    private const TIPOS_CUADRICULA = ['cuadricula', 'cuadricula_trama'];

    public function validateServiceInputs(array $inputs): array
    {
        $rules = [
            'medidaA' => 'required|numeric|min:0.1|max:50',
            'medidaB' => 'required|numeric|min:0.1|max:50',
            'alto' => 'required|numeric|min:0.1|max:10',
            'n_columnas' => 'required|integer|min:1|max:20',
            'n_bajantes' => 'required|integer|min:1|max:10',
            'anillos' => 'required|integer|min:0|max:50'
        ];
        
        $messages = [
            'medidaA.required' => 'La medida A es obligatoria.',
            'medidaA.numeric' => 'La medida A debe ser un número.',
            'medidaA.min' => 'La medida A debe ser mayor a 0.1m.',
            'medidaA.max' => 'La medida A no puede ser mayor a 50m.',
            'medidaB.required' => 'La medida B es obligatoria.',
            'medidaB.numeric' => 'La medida B debe ser un número.',
            'medidaB.min' => 'La medida B debe ser mayor a 0.1m.',
            'medidaB.max' => 'La medida B no puede ser mayor a 50m.',
            'alto.required' => 'El alto es obligatorio.',
            'alto.numeric' => 'El alto debe ser un número.',
            'alto.min' => 'El alto debe ser mayor a 0.1m.',
            'alto.max' => 'El alto no puede ser mayor a 10m.',
            'n_columnas.required' => 'El número de columnas es obligatorio.',
            'n_columnas.integer' => 'El número de columnas debe ser un entero.',
            'n_columnas.min' => 'Debe haber al menos 1 columna.',
            'n_columnas.max' => 'No puede haber más de 20 columnas.',
            'n_bajantes.required' => 'El número de bajantes es obligatorio.',
            'n_bajantes.integer' => 'El número de bajantes debe ser un entero.',
            'n_bajantes.min' => 'Debe haber al menos 1 bajante.',
            'n_bajantes.max' => 'No puede haber más de 10 bajantes.',
            'anillos.required' => 'El número de anillos es obligatorio.',
            'anillos.integer' => 'El número de anillos debe ser un entero.',
            'anillos.min' => 'El número de anillos no puede ser negativo.',
            'anillos.max' => 'No puede haber más de 50 anillos.'
        ];
        
        try {
            return validator($inputs, $rules, $messages)->validate();
        } catch (ValidationException $e) {
            return ['errors' => $e->errors()];
        }
    }

    public function validateGridInputs(array $inputs, string $gridType): array
    {
        if (!in_array($gridType, self::TIPOS_CUADRICULA)) {
            return []; // No requiere validación de cuadrícula
        }
        
        $rules = [
            'medidaACuadricula' => 'required|numeric|min:0.1|max:50',
            'medidaBCuadricula' => 'required|numeric|min:0.1|max:50',
            'distanciaPalillaje' => 'required|numeric|min:0.1|max:5',
            'altoCuadricula' => 'required|numeric|min:0.1|max:10'
        ];
        
        $messages = [
            'medidaACuadricula.required' => 'La medida A de la cuadrícula es obligatoria.',
            'medidaACuadricula.numeric' => 'La medida A de la cuadrícula debe ser un número.',
            'medidaACuadricula.min' => 'La medida A de la cuadrícula debe ser mayor a 0.1m.',
            'medidaACuadricula.max' => 'La medida A de la cuadrícula no puede ser mayor a 50m.',
            'medidaBCuadricula.required' => 'La medida B de la cuadrícula es obligatoria.',
            'medidaBCuadricula.numeric' => 'La medida B de la cuadrícula debe ser un número.',
            'medidaBCuadricula.min' => 'La medida B de la cuadrícula debe ser mayor a 0.1m.',
            'medidaBCuadricula.max' => 'La medida B de la cuadrícula no puede ser mayor a 50m.',
            'distanciaPalillaje.required' => 'La distancia de palillaje es obligatoria.',
            'distanciaPalillaje.numeric' => 'La distancia de palillaje debe ser un número.',
            'distanciaPalillaje.min' => 'La distancia de palillaje debe ser mayor a 0.1m.',
            'distanciaPalillaje.max' => 'La distancia de palillaje no puede ser mayor a 5m.',
            'altoCuadricula.required' => 'El alto de la cuadrícula es obligatorio.',
            'altoCuadricula.numeric' => 'El alto de la cuadrícula debe ser un número.',
            'altoCuadricula.min' => 'El alto de la cuadrícula debe ser mayor a 0.1m.',
            'altoCuadricula.max' => 'El alto de la cuadrícula no puede ser mayor a 10m.'
        ];
        
        try {
            return validator($inputs, $rules, $messages)->validate();
        } catch (ValidationException $e) {
            return ['errors' => $e->errors()];
        }
    }

    public function validateAllServices(array $services, array $inputsPorServicio): array
    {
        $allErrors = [];
        
        foreach ($services as $index => $servicio) {
            $inputs = $inputsPorServicio[$servicio['input_index']] ?? [];
            
            // Validar inputs del servicio
            $serviceValidation = $this->validateServiceInputs($inputs);
            if (isset($serviceValidation['errors'])) {
                foreach ($serviceValidation['errors'] as $field => $messages) {
                    foreach ($messages as $message) {
                        $allErrors["inputsPorServicio.{$servicio['input_index']}.{$field}"][] = $message;
                    }
                }
            }
            
            // Validar inputs de cuadrícula
            $gridValidation = $this->validateGridInputs($inputs, $servicio['selected_cuadricula']);
            if (isset($gridValidation['errors'])) {
                foreach ($gridValidation['errors'] as $field => $messages) {
                    foreach ($messages as $message) {
                        $allErrors["inputsPorServicio.{$servicio['input_index']}.{$field}"][] = $message;
                    }
                }
            }
        }
        
        return $allErrors;
    }
}
