<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Cotización {{ $numero_cotizacion }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header-left h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .header-left .company-tagline {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }

        .header-right {
            text-align: right;
            font-size: 11px;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin-top: 12px;
        }

        .info-box h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .service-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .service-subtitle {
            font-size: 11px;
            color: #666;
        }

        .price-main {
            font-weight: bold;
            text-align: right;
        }

        .price-breakdown {
            font-size: 11px;
            color: #666;
            text-align: right;
            margin-top: 3px;
        }

        .summary-section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .alert-section {
            margin-top: 30px;
            padding: 15px;
            border: 1px solid #e67e22;
            border-radius: 5px;
            background-color: #fdf6e3;
        }

        .alert-section h4 {
            margin: 0 0 10px 0;
            color: #d68910;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 11px;
        }

        h2 {
            margin-top: 30px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <h1>Estudio 3a</h1>
            <div class="company-tagline">Hacemos más que pérgolas; creamos espacios perfectos para compartir.</div>
        </div>
        <div class="header-right">
            <div><strong>Cotización:</strong> {{ $numero_cotizacion }}</div>
            <div><strong>Fecha de Emisión:</strong> {{ $fecha_emision }}</div>
            <div><strong>Válida hasta:</strong> {{ $vigencia }}</div>
        </div>
    </div>

    <!-- Información del Cliente -->
    <div class="info-section">
        <div class="info-box">
            <h3>Información del Cliente</h3>
            <div class="info-row">
                <span class="info-label">Nombre:</span>
                <span>{{ $cliente['nombre'] }}</span>
            </div>
            @if($cliente['dni'])
            <div class="info-row">
                <span class="info-label">DNI:</span>
                <span>{{ $cliente['dni'] }}</span>
            </div>
            @endif
            @if($cliente['telefono'])
            <div class="info-row">
                <span class="info-label">Teléfono:</span>
                <span>{{ $cliente['telefono'] }}</span>
            </div>
            @endif
            @if($cliente['provincia'])
            <div class="info-row">
                <span class="info-label">Provincia:</span>
                <span>{{ $cliente['provincia'] }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Servicios Cotizados -->
    <h2>Servicios Cotizados</h2>
    <table>
        <thead>
            <tr>
                <th>Descripción del Servicio</th>
                <th style="text-align: right;">Precio</th>
            </tr>
        </thead>
        <tbody>
            @php
                $serviciosAgrupados = [];
                $currentService = null;
                
                foreach($servicios as $servicio) {
                    if (str_contains($servicio['tipo'], 'Pergola')) {
                        // Es una pérgola, iniciar nuevo grupo
                        if ($currentService) {
                            $serviciosAgrupados[] = $currentService;
                        }
                        $currentService = [
                            'pergola' => $servicio,
                            'cuadricula' => null
                        ];
                    } else {
                        // Es una cuadrícula, agregar al servicio actual
                        if ($currentService) {
                            $currentService['cuadricula'] = $servicio;
                        }
                    }
                }
                
                // Agregar el último servicio
                if ($currentService) {
                    $serviciosAgrupados[] = $currentService;
                }
            @endphp

            @foreach($serviciosAgrupados as $servicioGroup)
                @php
                    $pergola = $servicioGroup['pergola'];
                    $cuadricula = $servicioGroup['cuadricula'];
                    $precioTotal = $pergola['precio'] + ($cuadricula ? $cuadricula['precio'] : 0);
                @endphp
                
                <tr>
                    <td>
                        <div class="service-title">{{ $pergola['tipo'] }}</div>
                        @if($cuadricula)
                            <div class="service-subtitle">• Incluye {{ $cuadricula['tipo'] }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="price-main">${{ number_format($precioTotal, 2) }}</div>
                        @if($cuadricula && $cuadricula['precio'] > 0)
                            <div class="price-breakdown">
                                Pérgola: ${{ number_format($pergola['precio'], 2) }}<br>
                                {{ $cuadricula['tipo'] }}: ${{ number_format($cuadricula['precio'], 2) }}
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Resumen Financiero -->
    <div class="summary-section">
        <h3 style="margin: 0 0 15px 0; color: #333;">Resumen Financiero</h3>
        <table style="margin: 0;">
            <tr>
                <td>Precio de Venta al Público (PVP)</td>
                <td style="text-align: right;">${{ number_format($resumen_financiero['pvp'], 2) }}</td>
            </tr>
            <tr>
                <td>IVA (15%)</td>
                <td style="text-align: right;">${{ number_format($resumen_financiero['iva'], 2) }}</td>
            </tr>
            <tr style="background-color: #f5f5f5; font-weight: bold;">
                <td>TOTAL GENERAL</td>
                <td style="text-align: right;">${{ number_format($resumen_financiero['total'], 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Información Importante -->
    <div class="alert-section">
        <h4>Información Importante</h4>
        <p style="margin: 0; line-height: 1.4;">
            Esta cotización tiene una vigencia de 30 días a partir de la fecha de emisión. 
            Los precios pueden estar sujetos a variaciones según la disponibilidad de materiales y condiciones del mercado.
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div style="font-weight: bold; margin-bottom: 5px;">Gracias por confiar en nuestros servicios</div>
        <div>Esta cotización ha sido generada automáticamente por el sistema de cotizaciones.</div>
    </div>
</body>

</html>
