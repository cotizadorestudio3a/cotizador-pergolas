<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationPdf extends Model
{
    protected $fillable = [
        'quotation_id',
        'quotation_item_id',
        'pdf_type',
        'title',
        'file_path',
        'file_name',
        'service_variant_id',
        'variant_name',
        'service_index',
        'file_size',
        'generated_at',
        'status'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'file_size' => 'integer',
        'service_variant_id' => 'integer',
        'service_index' => 'integer',
    ];

    /**
     * Relación con la cotización
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * Relación con el item de cotización (opcional, solo para PDFs de producción)
     */
    public function quotationItem(): BelongsTo
    {
        return $this->belongsTo(QuotationItem::class);
    }

    /**
     * Relación con la variante del servicio
     */
    public function serviceVariant(): BelongsTo
    {
        return $this->belongsTo(ServiceVariants::class, 'service_variant_id');
    }

    /**
     * Scope para obtener solo PDFs comerciales
     */
    public function scopeCommercial($query)
    {
        return $query->where('pdf_type', 'comercial');
    }

    /**
     * Scope para obtener solo PDFs de producción
     */
    public function scopeProduction($query)
    {
        return $query->whereIn('pdf_type', ['produccion_pergola', 'produccion_cuadricula']);
    }

    /**
     * Scope para obtener PDFs por tipo
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('pdf_type', $type);
    }

    /**
     * Obtener la URL completa del archivo
     */
    public function getFullUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Obtener la URL pública del archivo
     */
    public function getPublicUrl(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Verificar si el archivo existe físicamente
     */
    public function fileExists(): bool
    {
        return file_exists(storage_path('app/public/' . $this->file_path));
    }

    /**
     * Obtener el tamaño del archivo en formato legible
     */
    public function getFormattedFileSize(): string
    {
        if (!$this->file_size) {
            return 'Desconocido';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
