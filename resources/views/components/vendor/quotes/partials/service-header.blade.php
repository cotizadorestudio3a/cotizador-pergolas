{{-- Encabezado del servicio --}}
<div class="flex items-start justify-between mb-3">
    <div class="flex-1">
        <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary transition-colors">
            {{ $variant->service->name }}
        </h3>
        <span class="inline-block mt-1 px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full">
            {{ $variant->name }}
        </span>
    </div>
</div>
