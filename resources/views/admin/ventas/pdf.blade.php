<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Venta #{{ $venta->id }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }

        .header .empresa h2 {
            margin: 0 0 5px 0;
            font-size: 22px;
            font-weight: 700;
        }

        .header .empresa p {
            margin: 2px 0;
            font-size: 12px;
        }

        .header .venta {
            text-align: right;
        }

        .header .venta h3 {
            margin: 0 0 8px 0;
            font-size: 16px;
            font-weight: 500;
            color: #e53935;
        }

        .header .info-factura p {
            margin: 2px 0;
            font-size: 12px;
            color: #555;
        }

        /* Section Titles */
        .section {
            margin-bottom: 20px;
        }

        .section h3 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
            padding: 6px 12px;
            background-color: #f0f0f0;
            border-left: 4px solid #3b82f6;
            border-radius: 4px;
        }

        /* Info Cliente */
        .cliente-info {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .cliente-info div {
            width: 48%;
            margin-bottom: 6px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th,
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: #3b82f6;
            color: #fff;
            font-weight: 500;
            font-size: 11px;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* Totals */
        .totales {
            width: 60%;
            margin-left: auto;
            margin-right: right;
            margin-top: 20px;
        }

        .totales table {
            width: 100%;
            border: none;
        }

        .totales th {
            text-align: left;
            font-weight: 500;
            font-size: 12px;
            background-color: transparent;
            color: #555;
        }

        .totales td {
            text-align: right;
            font-weight: 500;
            font-size: 12px;
        }

        .totales .total-pagar {
            font-size: 15px;
            font-weight: 700;
            background-color: #e3f2fd;
            color: #1e40af;
            padding: 4px 8px;
            border-radius: 4px;
        }

        /* Footer */
        .agradecimiento {
            text-align: center;
            margin-top: 35px;
            font-size: 11px;
            color: #999;
        }


    </style>
</head>

<body>
    <div class="container">

        <!-- Encabezado -->
        <div class="header"
            style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid #ddd;">

            <!-- Logo + Info de la empresa -->
            <div style="align-items: center;">
                <!-- Logo -->
                <div style="margin-center: 15px;">
                    <img src="{{ public_path('utils/RDM-V2.jpg') }}" alt="Logo de la Empresa"
                        style="height:60px; width:auto;">
                </div>

                <!-- Información de la empresa -->
                <div class="empresa" style="text-align: left;">
                    <p style="margin:2px 0; font-size:12px; color:#555;">Alvear 585, San Cristobal, Santa Fe</p>
                    <p style="margin:2px 0; font-size:12px; color:#555;">CUIT:
                        {{ optional($venta->cliente)->cuit_dni ?? 'Sin dato' }}</p>
                </div>
            </div>

            <!-- Info de la venta -->
            <div class="venta" style="text-align:right;">
                <h3 style="color:#1e40af; margin:0 0 8px 0;">DOC. NO VALIDADO COMO FACTURA | {{ $venta->tipo_comprobante }}</h3>
                <div class="info-factura" style="padding:10px 12px; border-radius:6px; background-color:#f3f4f6;">
                    <p style="margin:2px 0;"><strong>Fecha:</strong>
                        {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }} | <strong>Venta n°:</strong>
                        {{ $venta->id }}</p>
                    <p style="margin:2px 0;"><strong>Tipo de Comprobante:</strong> {{ $venta->tipo_comprobante }} |
                        <strong>Condición de Pago:</strong> {{ $venta->condicion_pago }}</p>
                    <p style="margin:2px 0;"><strong>N° Comprobante:</strong> {{ $venta->numero_comprobante }} |
                        <strong>Estado:</strong> {{ $venta->estado_venta }}</p>
                </div>
            </div>

        </div>



        <!-- Datos del Cliente -->
        <div class="venta">
            <h3 style="color:#1e40af; font-size:16px;">DATOS DEL CLIENTE</h3>
            <div class="info-cliente" style="background-color:#f3f4f6; padding:10px 12px; border-radius:6px;">
                <p style="margin:2px 0;"><strong>Razón Social:</strong>
                    {{ optional($venta->cliente)->RazonSocial ?? 'Sin dato' }} | <strong>CUIT/DNI:</strong>
                    {{ optional($venta->cliente)->cuit_dni ?? 'Sin dato' }}</p>
                <p style="margin:2px 0;"><strong>Localidad:</strong> {{ optional($venta->cliente)->Localidad ?? '' }} |
                    <strong>Domicilio:</strong> {{ optional($venta->cliente)->Domicilio ?? '' }}
                </p>
                <p style="margin:2px 0;"><strong>Teléfono:</strong> {{ optional($venta->cliente)->Telefono ?? '' }}</p>
            </div>
        </div>

        <!-- Productos -->
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
                            <td>{{ \Illuminate\Support\Str::limit(optional($detalle->producto)->nombre ?? 'Sin producto', 40) }}
                            </td>
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

        <!-- Servicios -->
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
            $importeTotal = $subtotalProductos + $subtotalServicios;
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
                    <th class="total-pagar">Total a Pagar</th>
                    <td class="total-pagar">${{ number_format($importeTotal, 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Sección de Notas o Mensaje de Agradecimiento -->
        <div class="agradecimiento">
            <p>Los precios, la disponibilidad y los servicios ofrecidos son sujetos a cambios sin previo aviso.</p>
            <p>Gracias por tu compra. Si tienes alguna pregunta sobre esta factura, contáctanos.</p>
            <p>Teléfono: [03408-15675519] | Email: [info@rdm.com]</p>
        </div>

    </div>
</body>

</html>
