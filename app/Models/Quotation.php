<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'total',
        'iva',
        'pvp'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'iva' => 'decimal:2',
        'pvp' => 'decimal:2',
    ];

    /**
     * Relación con el cliente
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relación con el usuario (vendor)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los items de la cotización
     */
    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    /**
     * Relación con los PDFs de la cotización
     */
    public function pdfs(): HasMany
    {
        return $this->hasMany(QuotationPdf::class);
    }

    /**
     * Alias para la relación de PDFs
     */
    public function quotationPdfs(): HasMany
    {
        return $this->pdfs();
    }

    /**
     * Obtener solo PDFs comerciales
     */
    public function commercialPdfs(): HasMany
    {
        return $this->pdfs()->commercial();
    }

    /**
     * Obtener solo PDFs de producción
     */
    public function productionPdfs(): HasMany
    {
        return $this->pdfs()->production();
    }

    /**
     * Alias para quotationItems (para compatibilidad)
     */
    public function items(): HasMany
    {
        return $this->quotationItems();
    }

    /**
     * Obtener todos los PDFs organizados por tipo
     */
    public function getPdfsGroupedByType(): array
    {
        $pdfs = $this->pdfs()->orderBy('generated_at', 'desc')->get();
        
        return [
            'comercial' => $pdfs->where('pdf_type', 'comercial'),
            'produccion_pergola' => $pdfs->where('pdf_type', 'produccion_pergola'),
            'produccion_cuadricula' => $pdfs->where('pdf_type', 'produccion_cuadricula'),
        ];
    }

    /**
     * Obtener el último PDF comercial generado
     */
    public function getLatestCommercialPdf(): ?QuotationPdf
    {
        return $this->commercialPdfs()->latest('generated_at')->first();
    }

    /**
     * Verificar si tiene PDFs generados
     */
    public function hasPdfs(): bool
    {
        return $this->pdfs()->exists();
    }
}