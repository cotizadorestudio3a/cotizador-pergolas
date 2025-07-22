<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Cotización {{ $numero_cotizacion }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #4a90e2;
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }

        .header .numero {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            background-color: #f8f9fa;
            color: #4a90e2;
            font-size: 14px;
            font-weight: bold;
            padding: 8px 12px;
            border-left: 4px solid #4a90e2;
            margin-bottom: 15px;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 4px 0;
            width: 25%;
            color: #555;
        }

        .info-value {
            display: table-cell;
            padding: 4px 0;
            padding-left: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #4a90e2;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 8px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            font-size: 11px;
            color: #666;
        }

        .vigencia {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            border-radius: 4px;
            margin-top: 20px;
        }

        .amount {
            font-weight: bold;
            color: #4a90e2;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>COTIZACIÓN</h1>
        <div class="numero">{{ $numero_cotizacion }}</div>
        <div style="margin-top: 10px; color: #666;">
            <strong>Fecha de Emisión:</strong> {{ $fecha_emision }} | 
            <strong>Válida hasta:</strong> {{ $vigencia }}
        </div>
    </div>

    <!-- Información del Cliente -->
    <div class="section">
        <div class="section-title">INFORMACIÓN DEL CLIENTE</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nombre:</div>
                <div class="info-value">{{ $cliente['nombre'] }}</div>
            </div>
            @if($cliente['dni'])
            <div class="info-row">
                <div class="info-label">DNI:</div>
                <div class="info-value">{{ $cliente['dni'] }}</div>
            </div>
            @endif
            @if($cliente['telefono'])
            <div class="info-row">
                <div class="info-label">Teléfono:</div>
                <div class="info-value">{{ $cliente['telefono'] }}</div>
            </div>
            @endif
            @if($cliente['provincia'])
            <div class="info-row">
                <div class="info-label">Provincia:</div>
                <div class="info-value">{{ $cliente['provincia'] }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Servicio Cotizado -->
        <!-- Servicio Cotizado -->
    <div class="section">
        <div class="section-title">SERVICIOS COTIZADOS</div>
        
        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th class="text-right">Precio</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicios as $servicio)
                <tr>
                    <td>
                        <strong>{{ $servicio['tipo'] }}</strong><br>
                        <small>
                            Dimensiones: {{ $servicio['medidas']['medidaA'] }}m x {{ $servicio['medidas']['medidaB'] }}m<br>
                            @if($servicio['medidas']['alto'] > 0)
                                Altura: {{ $servicio['medidas']['alto'] }}m<br>
                            @endif
                            Área total: {{ number_format($servicio['medidas']['area'], 2) }} m²
                        </small>
                    </td>
                    <td class="text-right amount">
                        @if($servicio['precio'] > 0)
                            ${{ number_format($servicio['precio'], 2) }}
                        @else
                            <span style="font-style: italic; color: #666;">Incluido</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Resumen Financiero -->
    <div class="section">
        <div class="section-title">RESUMEN FINANCIERO</div>
        
        <table>
            <tbody>
                <tr style="border-top: 2px solid #4a90e2;">
                    <td><strong>Precio de Venta al Público (PVP)</strong></td>
                    <td class="text-right"><strong class="amount">${{ number_format($resumen_financiero['pvp'], 2) }}</strong></td>
                </tr>
                <tr>
                    <td>IVA (15%)</td>
                    <td class="text-right">${{ number_format($resumen_financiero['iva'], 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td><strong>TOTAL GENERAL</strong></td>
                    <td class="text-right"><strong class="amount" style="font-size: 16px;">${{ number_format($resumen_financiero['total'], 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Vigencia de la Cotización -->
    <div class="vigencia">
        <strong>⚠️ Importante:</strong> Esta cotización tiene una vigencia de 30 días a partir de la fecha de emisión. 
        Los precios pueden estar sujetos a variaciones según la disponibilidad de materiales y condiciones del mercado.
    </div>

    <!-- Footer -->
    <div class="footer">
        <div style="text-align: center;">
            <p><strong>Gracias por confiar en nuestros servicios</strong></p>
            <p>Esta cotización ha sido generada automáticamente por el sistema de cotizaciones.</p>
        </div>
    </div>
</body>

</html>
