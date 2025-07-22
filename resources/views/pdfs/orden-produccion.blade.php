<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Orden de Producción</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
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
            padding: 5px;
            text-align: left;
        }

        h2 {
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <h1>Orden de Producción</h1>

    <p><strong>Medidas:</strong> {{ $medidas['medidaA'] }}m x {{ $medidas['medidaB'] }}m</p>
    <p><strong>Altura:</strong> {{ $medidas['alto'] }}m</p>
    <p><strong>Área:</strong> {{ number_format($medidas['area'], 2) }} m²</p>

    <h2>Detalle de Materiales</h2>
    <table>
        <thead>
            <tr>
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
                    <td>{{ $nombre }}</td>
                    <td>{{ number_format($datos['cantidad'], 2) }}</td>
                    <td>{{ number_format($datos['unidades'], 2) }}</td>
                    <td>${{ number_format($datos['precio_unitario'], 2) }}</td>
                    <td>${{ number_format($datos['total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (!empty($extras['estrategia_andamios']) || !empty($extras['nota_pago_por_dia']))
        <div style="margin-top: 30px;">
            <h4 style="font-weight: bold; font-size: 16px;">Notas Operativas</h4>
            <ul style="font-size: 14px;">
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
