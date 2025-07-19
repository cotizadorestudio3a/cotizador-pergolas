{{-- Indicador de selección --}}
@if ($isSelected)
    <div class="absolute top-4 right-4 w-5 h-5 bg-primary rounded-full flex items-center justify-center shadow-sm">
        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span class="sr-only">Seleccionado</span>
    </div>
@endif
