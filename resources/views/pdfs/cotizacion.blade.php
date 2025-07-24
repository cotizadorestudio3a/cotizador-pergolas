<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Cotización {{ $numero_cotizacion }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #1a1a1a;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            background: #000000;
            border-radius: 12px;
            margin-bottom: 24px;
            color: #ffffff;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: #ffffff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #000000;
            font-size: 12px;
        }

        .company-info h1 {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
            letter-spacing: -0.3px;
        }

        .company-tagline {
            font-size: 10px;
            opacity: 0.8;
            margin: 2px 0 0 0;
            font-weight: 400;
        }

        .quotation-info {
            text-align: right;
        }

        .quotation-number {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        .quotation-dates {
            font-size: 9px;
            opacity: 0.8;
            margin-top: 4px;
            font-weight: 400;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 16px 0;
            color: #000000;
            letter-spacing: -0.2px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .info-label {
            font-size: 9px;
            font-weight: 500;
            color: #666666;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .info-value {
            font-size: 11px;
            font-weight: 500;
            color: #1a1a1a;
        }

        .services-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e5e5e5;
        }

        .services-table thead th {
            background: #000000;
            color: #ffffff;
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            letter-spacing: 0.2px;
        }

        .services-table thead th:first-child {
            border-top-left-radius: 10px;
        }

        .services-table thead th:last-child {
            border-top-right-radius: 10px;
        }

        .services-table tbody td {
            padding: 16px;
            border-bottom: 1px solid #f0f0f0;
            background: #ffffff;
        }

        .services-table tbody tr:last-child td {
            border-bottom: none;
        }

        .services-table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 10px;
        }

        .services-table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
        }

        .service-title {
            font-size: 13px;
            font-weight: 600;
            color: #1a1a1a;
            margin: 0 0 6px 0;
        }

        .service-subtitle {
            font-size: 10px;
            color: #666666;
            margin: 0;
            font-weight: 400;
        }

        .price-main {
            font-size: 16px;
            font-weight: 700;
            color: #000000;
            text-align: right;
        }

        .price-breakdown {
            font-size: 9px;
            color: #666666;
            text-align: right;
            margin-top: 4px;
            line-height: 1.3;
            font-weight: 400;
        }

        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .summary-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 11px;
        }

        .summary-table tr:last-child td {
            border-bottom: none;
            background: #000000;
            color: #ffffff;
            font-weight: 700;
            font-size: 14px;
        }

        .summary-table tr:last-child td:first-child {
            border-bottom-left-radius: 10px;
        }

        .summary-table tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
        }

        .text-right {
            text-align: right;
        }

        .alert {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 16px;
            margin: 20px 0;
        }

        .alert-title {
            font-weight: 600;
            margin: 0 0 6px 0;
            color: #1a1a1a;
            font-size: 11px;
        }

        .alert-text {
            margin: 0;
            color: #666666;
            line-height: 1.4;
            font-size: 10px;
            font-weight: 400;
        }

        .footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
            color: #666666;
            font-size: 10px;
        }

        .footer-title {
            font-weight: 600;
            color: #1a1a1a;
            margin: 0 0 6px 0;
            font-size: 11px;
        }

        .footer-subtitle {
            margin: 0;
            font-weight: 400;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div class="company-info">
                    <h1>Estudio 3a</h1>
                    <div class="company-tagline">Hacemos más que pérgolas; creamos espacios perfectos para compartir.</div>
                </div>
            </div>
            <div class="quotation-info">
                <div class="quotation-number">{{ $numero_cotizacion }}</div>
                <div class="quotation-dates">
                    Emitida: {{ $fecha_emision }}<br>
                    Válida hasta: {{ $vigencia }}
                </div>
            </div>
        </div>

        <!-- Información del Cliente -->
        <div class="card">
            <h2 class="card-title">Información del Cliente</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nombre</div>
                    <div class="info-value">{{ $cliente['nombre'] }}</div>
                </div>
                @if($cliente['dni'])
                <div class="info-item">
                    <div class="info-label">DNI</div>
                    <div class="info-value">{{ number_format($cliente['dni'], 0, '', '.') }}</div>
                </div>
                @endif
                @if($cliente['telefono'])
                <div class="info-item">
                    <div class="info-label">Teléfono</div>
                    <div class="info-value">{{ $cliente['telefono'] }}</div>
                </div>
                @endif
                @if($cliente['provincia'])
                <div class="info-item">
                    <div class="info-label">Provincia</div>
                    <div class="info-value">{{ $cliente['provincia'] }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Servicios Cotizados -->
        <div class="card">
            <h2 class="card-title">Servicios Cotizados</h2>
            
            <table class="services-table">
                <thead>
                    <tr>
                        <th>Descripción del Servicio</th>
                        <th class="text-right">Precio</th>
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
        </div>

        <!-- Resumen Financiero -->
        <div class="card">
            <h2 class="card-title">Resumen Financiero</h2>
            
            <table class="summary-table">
                <tr>
                    <td>Precio de Venta al Público (PVP)</td>
                    <td class="text-right">${{ number_format($resumen_financiero['pvp'], 2) }}</td>
                </tr>
                <tr>
                    <td>IVA (15%)</td>
                    <td class="text-right">${{ number_format($resumen_financiero['iva'], 2) }}</td>
                </tr>
                <tr>
                    <td>TOTAL GENERAL</td>
                    <td class="text-right">${{ number_format($resumen_financiero['total'], 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Información Importante -->
        <div class="alert">
            <div class="alert-title">Información Importante</div>
            <div class="alert-text">
                Esta cotización tiene una vigencia de 30 días a partir de la fecha de emisión. 
                Los precios pueden estar sujetos a variaciones según la disponibilidad de materiales y condiciones del mercado.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-title">Gracias por confiar en nuestros servicios</div>
            <div class="footer-subtitle">Esta cotización ha sido generada automáticamente por el sistema de cotizaciones.</div>
        </div>
    </div>
</body>

</html>
