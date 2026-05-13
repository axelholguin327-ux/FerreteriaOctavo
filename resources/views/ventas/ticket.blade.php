<!DOCTYPE html>
<html>
<head>
    <style>
        * { font-family: 'Courier', sans-serif; font-size: 12px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .header { margin-bottom: 10px; border-bottom: 1px dashed #000; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; }
        .total-section { border-top: 1px dashed #000; margin-top: 10px; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header text-center">
        <h2 style="font-size: 16px; margin: 0;">FERRETERÍA OCTAVO</h2>
        <p>Desarrollo Integral<br>
        Folio: #{{ $venta->id }}<br>
        Fecha: {{ $venta->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <p><strong>Cliente:</strong> {{ $venta->cliente_nombre ?? 'Cliente General' }}<br>
    <strong>Vendedor:</strong> {{ $venta->user->name }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-left">Cant.</th>
                <th class="text-left">Prod.</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->orderItems as $item)
            <tr>
                <td>{{ $item->cantidad }}</td>
                <td>{{ $item->product->nombre }}</td>
                <td class="text-right">${{ number_format($item->precio_unitario * $item->cantidad, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="text-right bold" style="font-size: 14px;">
            TOTAL: ${{ number_format($venta->total, 2) }}
        </div>
    </div>

    <div class="text-center" style="margin-top: 20px;">
        <p>¡Gracias por su compra!<br>Favor de conservar su ticket.</p>
    </div>
</body>
</html>