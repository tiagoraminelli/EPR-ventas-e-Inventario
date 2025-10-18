<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reparación #{{ $reparacion->id }}</title>
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

        .header .reparacion {
            text-align: right;
        }

        .header .reparacion h3 {
            margin: 0 0 8px 0;
            font-size: 16px;
            font-weight: 500;
            color: #e53935;
        }

        .header .info-reparacion p {
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
        <div class="header">

            <!-- Logo + Info de la empresa -->
            <div>
                <div style="margin-bottom:15px;">
                    <img src="{{ public_path('utils/RDM-V2.jpg') }}" alt="Logo" style="height:60px; width:auto;">
                </div>
                <div class="empresa">
                    <p>Alvear 585, San Cristobal, Santa Fe</p>
                    <p>CUIT: {{ optional($reparacion->cliente)->cuit_dni ?? 'Sin dato' }}</p>
                </div>
            </div>

            <!-- Info de la reparación -->
            <div class="reparacion">
                <h3 style="color:#1e40af;">DETALLE DE REPARACIÓN | Cod.Unico: {{ $reparacion->codigo_unico }} | {{ $reparacion->id }}</h3>
                <div class="info-reparacion" style="padding:10px 12px; border-radius:6px; background-color:#f3f4f6;">
                    <p><strong>Fecha Ingreso:</strong>
                        {{ \Carbon\Carbon::parse($reparacion->fecha_ingreso)->format('d/m/Y') }}
                        | <strong>Reparación n°:</strong> {{ $reparacion->id }}</p>
                    <p><strong>Estado:</strong> {{ $reparacion->estado_reparacion }} | <strong>Reparable:</strong>
                        {{ $reparacion->reparable ? 'Sí' : 'No' }}</p>
                </div>
            </div>

        </div>

        <!-- Datos del Cliente -->
        <div class="section">
            <h3>DATOS DEL CLIENTE</h3>
            <div class="cliente-info" style="background-color:#f3f4f6; padding:10px 12px; border-radius:6px;">
                <p><strong>Razón Social:</strong> {{ optional($reparacion->cliente)->RazonSocial ?? 'Sin dato' }} |
                    <strong>CUIT/DNI:</strong> {{ optional($reparacion->cliente)->cuit_dni ?? 'Sin dato' }}
                </p>
                <p><strong>Localidad:</strong> {{ optional($reparacion->cliente)->Localidad ?? '' }} |
                    <strong>Domicilio:</strong> {{ optional($reparacion->cliente)->Domicilio ?? '' }}
                </p>
                <p><strong>Teléfono:</strong> {{ optional($reparacion->cliente)->Telefono ?? '' }}</p>
            </div>
        </div>

        <!-- Detalles de Reparación -->
        <div class="section">
            <h3>DETALLES DE LA REPARACIÓN</h3>
            <table>
                <thead>
                    <tr>
                        <th>Equipo</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $reparacion->equipo_descripcion }}</td>
                        <td>{{ $reparacion->equipo_marca ?? 'Sin dato' }}</td>
                        <td>{{ $reparacion->equipo_modelo ?? 'Sin dato' }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Sección de Daños y Diagnóstico -->
            <div style="margin-top: 15px; padding:10px 12px; background-color:#f3f4f6; border-radius:6px;">
                <h4 style="margin-bottom:5px; color:#1e40af;">DAÑOS DETECTADOS</h4>
                <p style="margin-bottom:10px;">{{ $reparacion->descripcion_danio ?? 'Sin descripción' }}</p>

                <h4 style="margin-bottom:5px; color:#1e40af;">DIAGNÓSTICO DE LA REPARACIÓN</h4>
                <p>{{ $reparacion->solucion_aplicada ?? 'Sin diagnóstico' }}</p>
            </div>
        </div>




        <!-- Servicios Aplicados -->
        <div class="section">
            <h3>Servicios Aplicados</h3>
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
                    @forelse ($reparacion->reparacionServicios as $rs)
                        <tr>
                            <td>{{ $rs->servicio->nombre ?? 'Sin Servicio' }}</td>
                            <td>{{ $rs->cantidad }}</td>
                            <td>${{ number_format($rs->precio, 2, ',', '.') }}</td>
                            <td>${{ number_format($rs->cantidad * $rs->precio, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No hay servicios asociados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Productos Utilizados -->
        <div class="section">
            <h3>Productos Utilizados</h3>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reparacion->reparacionProductos as $rp)
                        <tr>
                            <td>{{ $rp->producto->nombre ?? 'Sin Producto' }}</td>
                            <td>{{ $rp->cantidad }}</td>
                            <td>${{ number_format($rp->precio, 2, ',', '.') }}</td>
                            <td>${{ number_format($rp->cantidad * $rp->precio, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No hay productos asociados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Totales -->
        @php
            $subtotalProductos = $reparacion->reparacionProductos->sum(fn($rp) => $rp->cantidad * $rp->precio);
            $subtotalServicios = $reparacion->reparacionServicios->sum(fn($rs) => $rs->cantidad * $rs->precio);
            $total = $subtotalProductos + $subtotalServicios;
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
                    <td class="total-pagar">${{ number_format($total, 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="agradecimiento">
            <p>Los precios, la disponibilidad y los servicios ofrecidos son sujetos a cambios sin previo aviso.</p>
            <p>Gracias por confiar en nosotros.</p>
            <p>Teléfono: [03408-15675519] | Email: [info@rdm.com]</p>
        </div>

    </div>
</body>

</html>
