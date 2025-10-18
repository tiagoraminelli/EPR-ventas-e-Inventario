<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Venta #{{ $venta->id }}</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        color: #333;
        margin: 0;
        padding: 0;
        background-color: #f8f9fa;
    }
    .container {
        display: block;
        width: 100%;
        max-width: 800px;
        margin: 20px auto;
        background: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }

    /* Encabezado */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 40px;
        padding-bottom: 25px;
        border-bottom: 2px solid #e9ecef;
    }
    .header .empresa h2 {
        font-size: 28px;
        font-weight: 700;
        color: #4a4a4a;
        margin: 0;
        letter-spacing: -0.5px;
    }
    .header .empresa p {
        font-size: 12px;
        margin: 2px 0;
        color: #777;
    }
    .header .venta {
        text-align: right;
    }
    .header .venta h3 {
        font-size: 20px;
        font-weight: 600;
        color: #d9534f;
        margin: 0 0 12px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .header .info-factura p {
        margin: 4px 0;
        font-weight: 500;
        color: #555;
    }
    .header .info-factura p strong {
        color: #333;
    }

    /* Secciones */
    .section {
        margin-bottom: 30px;
    }
    .section h3 {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        background-color: #f1f4f8;
        padding: 10px 20px;
        border-left: 4px solid #5cb85c;
        border-radius: 6px;
        margin-bottom: 15px;
    }

    /* Tabla de detalles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 0 auto;
        border-radius: 8px;
        overflow: hidden;
    }
    th, td {
        padding: 15px 20px;
        text-align: left;
        border-bottom: 1px solid #e9ecef;
    }
    th {
        background-color: #34495e;
        color: white;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: 0.5px;
    }
    tr:nth-child(even) {
        background-color: #fcfcfc;
    }

    /* Totales */
    .totales {
        margin-top: 40px;
        width: 60%;
        margin-left: auto;
    }
    .totales table {
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .totales th {
        background-color: #f1f4f8;
        color: #4a4a4a;
        font-weight: 600;
    }
    .totales td {
        font-weight: 600;
        text-align: right;
    }
    .totales .total-pagar th, .totales .total-pagar td {
        font-size: 16px;
        font-weight: 700;
        background-color: #d4edda;
        color: #155724;
    }

    /* Footer */
    .footer {
        clear: both;
        text-align: center;
        margin-top: 60px;
        font-size: 11px;
        color: #999;
    }
</style>
</head>
<body>
<div class="container">

<!-- Encabezado -->
<div class="header section">
    <div class="empresa">
        <h2>RDM</h2>
        <p>Alvear 585, San Cristobal, Santa Fe</p>
        <p>CUIT: {{ optional($venta->cliente)->cuit_dni ?? 'Sin dato' }}</p>
    </div>
    <div class="venta">
        <h3>DOC. NO VALIDADO COMO FACTURA</h3>
        <div class="info-factura">
            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</p>
            <p><strong>Venta N°:</strong> {{ $venta->id }}</p>
            <p><strong>Tipo:</strong> {{ $venta->tipo_comprobante }}</p>
            <p><strong>Condición:</strong> {{ $venta->condicion_pago }}</p>
            <p><strong>N° Comprobante:</strong> {{ $venta->numero_comprobante }}</p>
            <p><strong>Estado:</strong> {{ $venta->estado_venta }}</p>
        </div>
    </div>
</div>

<!-- Datos del Cliente -->
<div class="section">
    <h3>Datos del Cliente</h3>
    <p><strong>Razón Social:</strong> {{ optional($venta->cliente)->RazonSocial ?? 'Sin dato' }}</p>
    <p><strong>Domicilio:</strong> {{ optional($venta->cliente)->Domicilio ?? '' }}</p>
    <p><strong>Localidad:</strong> {{ optional($venta->cliente)->Localidad ?? '' }}</p>
    <p><strong>CUIT/DNI:</strong> {{ optional($venta->cliente)->cuit_dni ?? 'Sin dato' }}</p>
    <p><strong>Teléfono:</strong> {{ optional($venta->cliente)->Telefono ?? '' }}</p>
</div>

<!-- Detalle de Productos -->
<div class="section">
    <h3>Detalle de Productos</h3>
    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Descuento</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($venta->detalleVentas as $detalle)
            <tr>
                <td>{{ \Illuminate\Support\Str::limit(optional($detalle->producto)->nombre ?? 'Sin producto', 40) }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>${{ number_format($detalle->precio_unitario, 2, ',', '.') }}</td>
                <td>
                    @if ($detalle->descuento > 0)
                        ${{ number_format($detalle->descuento, 2, ',', '.') }}
                    @else
                        Sin descuento
                    @endif
                </td>
                <td>${{ number_format($detalle->subtotal, 2, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No hay productos asociados a esta venta.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Detalle de Servicios -->
<div class="section">
    <h3>Detalle de Servicios</h3>
    <table>
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($venta->serviciosVentas as $sv)
            <tr>
                <td>{{ $sv->servicio->nombre ?? 'Servicio no disponible' }}</td>
                <td>{{ $sv->cantidad }}</td>
                <td>${{ number_format($sv->precio, 2, ',', '.') }}</td>
                <td>${{ number_format($sv->cantidad * $sv->precio, 2, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No hay servicios asociados a esta venta.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Totales -->
@php
    $subtotalProductos = $venta->detalleVentas->sum('subtotal');
    $subtotalServicios = $venta->serviciosVentas->sum(fn($sv) => $sv->cantidad * $sv->precio);
    $importeNeto = $subtotalProductos + $subtotalServicios;
    $importeTotal = $importeNeto;
@endphp
<div class="totales">
    <table>
        <tr>
            <th>Subtotal Productos</th>
            <td>${{ number_format($subtotalProductos, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Subtotal Servicios</th>
            <td>${{ number_format($subtotalServicios, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Subtotal General</th>
            <td>${{ number_format($importeNeto, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <th class="total-pagar">Total a Pagar</th>
            <td class="total-pagar">${{ number_format($importeTotal, 2, ',', '.') }}</td>
        </tr>
    </table>
</div>

<!-- Footer -->
<div class="footer">
    <p>Gracias por tu compra. Tel: 03408-15675519 | Email: info@rdm.com</p>
</div>

</div>
</body>
</html>
