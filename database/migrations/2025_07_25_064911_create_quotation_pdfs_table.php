<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quotation_pdfs', function (Blueprint $table) {
            $table->id();
            
            // Relación con la cotización (siempre requerido)
            $table->foreignId('quotation_id')->constrained('quotations')->onDelete('cascade');
            
            // Relación con quotation_item (opcional, solo para PDFs de producción)
            $table->foreignId('quotation_item_id')->nullable()->constrained('quotation_items')->onDelete('cascade');
            
            // Tipo de PDF
            $table->enum('pdf_type', ['comercial', 'produccion_pergola', 'produccion_cuadricula'])
                  ->comment('Tipo de PDF: comercial, produccion_pergola, produccion_cuadricula');
            
            // Información del PDF
            $table->string('title')->comment('Título del PDF como aparece en la lista');
            $table->string('file_path')->comment('Ruta del archivo PDF en storage');
            $table->string('file_name')->comment('Nombre original del archivo');
            
            // Información adicional del servicio (para PDFs de producción)
            $table->integer('service_variant_id')->nullable()->comment('ID de la variante del servicio');
            $table->string('variant_name')->nullable()->comment('Nombre de la variante para referencia');
            $table->integer('service_index')->nullable()->comment('Índice del servicio en la cotización');
            
            // Metadatos útiles
            $table->integer('file_size')->nullable()->comment('Tamaño del archivo en bytes');
            $table->timestamp('generated_at')->useCurrent()->comment('Fecha y hora de generación del PDF');
            $table->string('status')->default('generated')->comment('Estado del PDF: generated, downloaded, error');
            
            $table->timestamps();
            
            // Índices para consultas eficientes
            $table->index(['quotation_id', 'pdf_type']);
            $table->index(['quotation_item_id']);
            $table->index(['pdf_type']);
            $table->index(['generated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_pdfs');
    }
};
