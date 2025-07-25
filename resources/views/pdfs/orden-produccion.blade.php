<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Orden de Producción - {{ $cotizacion['numero_cotizacion'] }}</title>
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

        .header-right {
            text-align: right;
            font-size: 11px;
        }

        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
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

        .codigo-material {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            background-color: #f5f5f5;
            text-align: center;
        }

        .nombre-material {
            font-weight: 500;
        }

        .columnas-section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .notas-section {
            margin-top: 30px;
            padding: 15px;
            border: 1px solid #e67e22;
            border-radius: 5px;
            background-color: #fdf6e3;
        }

        .notas-section h4 {
            margin: 0 0 10px 0;
            color: #d68910;
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

    <div class="header">
        <div class="header-left">
            <h1>Orden de Producción</h1>
            <p style="margin: 5px 0; font-size: 16px; font-weight: bold;">{{ $titulo['titulo_servicio'] }}</p>
        </div>
        <div class="header-right">
            <div><strong>Cotización:</strong> {{ $cotizacion['numero_cotizacion'] }}</div>
            <div><strong>Fecha de Orden:</strong> {{ $cotizacion['fecha_orden'] }}</div>
            <div><strong>Fecha de Emisión:</strong> {{ $cotizacion['fecha_emision'] }}</div>
            <div><strong>Vencimiento:</strong> {{ $cotizacion['fecha_vencimiento'] }}</div>
        </div>
    </div>

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

        <div class="info-box">
            <h3>Especificaciones Técnicas</h3>
            <div class="info-row">
                <span class="info-label">Medidas:</span>
                <span>{{ $medidas['medidaA'] }}m x {{ $medidas['medidaB'] }}m</span>
            </div>
            <div class="info-row">
                <span class="info-label">Altura:</span>
                <span>{{ $medidas['alto'] }}m</span>
            </div>
            <div class="info-row">
                <span class="info-label">Área:</span>
                <span>{{ number_format($medidas['area'], 2) }} m²</span>
            </div>
            @if(isset($medidas['n_columnas']))
            <div class="info-row">
                <span class="info-label">N° Columnas:</span>
                <span>{{ $medidas['n_columnas'] }}</span>
            </div>
            @endif
            @if(isset($medidas['n_bajantes']))
            <div class="info-row">
                <span class="info-label">N° Bajantes:</span>
                <span>{{ $medidas['n_bajantes'] }}</span>
            </div>
            @endif
            @if(isset($medidas['anillos']) && $medidas['anillos'] > 0)
            <div class="info-row">
                <span class="info-label">Anillos:</span>
                <span>{{ $medidas['anillos'] }}</span>
            </div>
            @endif
            @if(isset($tiempo_estimado))
            <div class="info-row">
                <span class="info-label">Tiempo Estimado:</span>
                <span>{{ $tiempo_estimado['dias'] }} días ({{ $tiempo_estimado['meses'] }} meses)</span>
            </div>
            @endif
        </div>
    </div>

    @if(!empty($columnas) && is_array($columnas))
    <div class="columnas-section">
        <h3 style="margin: 0 0 15px 0; color: #333;">Detalle de Columnas</h3>
        <table style="margin: 0;">
            <thead>
                <tr>
                    <th>N° Columna</th>
                    <th>Color</th>
                </tr>
            </thead>
            <tbody>
                @foreach($columnas as $index => $columna)
                @php
                    $color = $columna['color'] ?? 'azul';
                    
                    // Mapear color a texto
                    switch($color) {
                        case 'azul':
                            $colorTexto = 'Azul';
                            $colorHex = '#3B82F6';
                            break;
                        case 'negro':
                            $colorTexto = 'Negro';
                            $colorHex = '#000000';
                            break;
                        case 'blanco':
                            $colorTexto = 'Blanco';
                            $colorHex = '#FFFFFF';
                            break;
                        case 'gris':
                            $colorTexto = 'Gris';
                            $colorHex = '#6B7280';
                            break;
                        case 'rojo':
                            $colorTexto = 'Rojo';
                            $colorHex = '#EF4444';
                            break;
                        default:
                            $colorTexto = 'Azul';
                            $colorHex = '#3B82F6';
                            break;
                    }
                @endphp
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $columna['numero'] ?? ($index + 1) }}</td>
                    <td style="text-align: center;">
                        <div style="text-align: center;">
                            <div style="width: 16px; height: 16px; border-radius: 50%; background-color: {{ $colorHex }}; border: 1px solid #ccc; display: inline-block; margin-right: 6px; vertical-align: middle;"></div>
                            <span style="font-size: 11px; font-weight: 500; vertical-align: middle;">{{ $colorTexto }}</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <h2>Detalle de Materiales</h2>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Material</th>
                <th>Cantidad</th>
                <th>Unidades</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($materiales as $nombre => $datos)
                <tr>
                    <td class="codigo-material">{{ $datos['codigo'] }}</td>
                    <td class="nombre-material">{{ $datos['nombre'] }}</td>
                    <td>{{ number_format($datos['cantidad'], 2) }}</td>
                    <td>{{ number_format($datos['unidades'], 2) }}</td>
                    <td>${{ number_format($datos['precio_unitario'], 2) }}</td>
                    <td>${{ number_format($datos['total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (!empty($extras['estrategia_andamios']) || !empty($extras['nota_pago_por_dia']))
        <div class="notas-section">
            <h4>Notas Operativas</h4>
            <ul style="margin: 0; padding-left: 20px;">
                @if (!empty($extras['estrategia_andamios']))
                    <li><strong>Andamios:</strong> {{ $extras['estrategia_andamios'] }}</li>
                @endif
                @if (!empty($extras['nota_pago_por_dia']))
                    <li><strong>Pergola:</strong> {{ $extras['nota_pago_por_dia'] }}</li>
                @endif
            </ul>
        </div>
    @endif

</body>

</html>
