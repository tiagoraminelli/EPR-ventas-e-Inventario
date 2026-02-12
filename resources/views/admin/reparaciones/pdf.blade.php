<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Reparación #{{ $reparacion->id }}</title>

<style>
    body {
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 11px;
        color: #000;
        margin: 0;
        padding: 25px;
    }

    .container {
        width: 100%;
    }

    .header {
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .header-table {
        width: 100%;
    }

    .header-left {
        width: 60%;
        vertical-align: top;
    }

    .header-right {
        width: 40%;
        text-align: right;
        vertical-align: top;
    }

    .logo {
        height: 55px;
        margin-bottom: 8px;
    }

    .titulo-documento {
        font-size: 16px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .codigo {
        font-size: 12px;
        font-weight: bold;
        margin-top: 5px;
    }

    .section-title {
        font-weight: bold;
        margin-top: 20px;
        margin-bottom: 8px;
        border-bottom: 1px solid #000;
        padding-bottom: 3px;
        font-size: 12px;
        text-transform: uppercase;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        border-bottom: 1px solid #000;
        text-align: left;
        padding: 6px 4px;
        font-size: 10px;
        text-transform: uppercase;
    }

    td {
        padding: 6px 4px;
        border-bottom: 1px solid #ccc;
    }

    .text-right {
        text-align: right;
    }

    .box {
        border: 1px solid #000;
        padding: 10px;
        margin-bottom: 10px;
    }

    .totales {
        width: 45%;
        margin-left: auto;
        margin-top: 15px;
    }

    .totales td {
        border: none;
        padding: 4px;
    }

    .total-final {
        border-top: 2px solid #000;
        font-weight: bold;
        font-size: 13px;
    }

    .footer {
        margin-top: 30px;
        border-top: 1px solid #000;
        padding-top: 8px;
        font-size: 9px;
        text-align: center;
    }

    .estado {
        border: 1px solid #000;
        padding: 3px 6px;
        display: inline-block;
        font-size: 10px;
        font-weight: bold;
        margin-top: 5px;
    }
</style>
</head>

<body>
<div class="container">

    <!-- HEADER -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <img src="{{ public_path('utils/RDM-V2.jpg') }}" class="logo">
                    <div>
                        Alvear 585 – San Cristóbal – Santa Fe<br>
                        Tel: 03408-15675519<br>
                        info@rdm.com.ar
                    </div>
                </td>

                <td class="header-right">
                    <div class="titulo-documento">
                        ORDEN DE REPARACIÓN
                    </div>
                    <div class="codigo">
                        N° {{ $reparacion->codigo_unico }}
                    </div>
                    <br>
                    <strong>Ingreso:</strong> {{ \Carbon\Carbon::parse($reparacion->fecha_ingreso)->format('d/m/Y') }}<br>
                    @if($reparacion->fecha_egreso)
                        <strong>Egreso:</strong> {{ \Carbon\Carbon::parse($reparacion->fecha_egreso)->format('d/m/Y') }}<br>
                    @endif
                    <div class="estado">
                        {{ $reparacion->estado_reparacion }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- CLIENTE -->
    <div class="section-title">Datos del Cliente</div>
    <div class="box">
        <strong>Razón Social:</strong> {{ optional($reparacion->cliente)->RazonSocial ?? '-' }}<br>
        <strong>CUIT/DNI:</strong> {{ optional($reparacion->cliente)->cuit_dni ?? '-' }}<br>
        <strong>Domicilio:</strong>
        {{ optional($reparacion->cliente)->Domicilio ?? '-' }}
        - {{ optional($reparacion->cliente)->Localidad ?? '' }}<br>
        <strong>Teléfono:</strong> {{ optional($reparacion->cliente)->Telefono ?? '-' }}<br>
        <strong>Email:</strong> {{ optional($reparacion->cliente)->Email ?? '-' }}
    </div>

    <!-- EQUIPO -->
    <div class="section-title">Equipo</div>
    <div class="box">
        <strong>Descripción:</strong> {{ $reparacion->equipo_descripcion }}<br>
        <strong>Marca:</strong> {{ $reparacion->equipo_marca ?? '-' }}<br>
        <strong>Modelo:</strong> {{ $reparacion->equipo_modelo ?? '-' }}
    </div>

    <!-- DAÑO -->
    <div class="section-title">Daños Detectados</div>
    <div class="box">
        {{ $reparacion->descripcion_danio ?? '-' }}
    </div>

    @if($reparacion->solucion_aplicada)
    <div class="section-title">Solución Aplicada</div>
    <div class="box">
        {{ $reparacion->solucion_aplicada }}
    </div>
    @endif

    <!-- SERVICIOS -->
    @php
        $subtotalServicios = $reparacion->reparacionServicios->sum(fn($rs) => $rs->cantidad * $rs->precio);
        $subtotalProductos = $reparacion->reparacionProductos->sum(fn($rp) => $rp->cantidad * $rp->precio);
        $total = $subtotalServicios + $subtotalProductos;
    @endphp

    @if($reparacion->reparacionServicios->count() > 0)
    <div class="section-title">Servicios</div>
    <table>
        <thead>
            <tr>
                <th>Servicio</th>
                <th style="width:10%">Cant.</th>
                <th style="width:20%" class="text-right">Precio</th>
                <th style="width:20%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($reparacion->reparacionServicios as $rs)
            <tr>
                <td>{{ $rs->servicio->nombre ?? '-' }}</td>
                <td>{{ $rs->cantidad }}</td>
                <td class="text-right">$ {{ number_format($rs->precio,2,',','.') }}</td>
                <td class="text-right">$ {{ number_format($rs->cantidad * $rs->precio,2,',','.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif

    <!-- PRODUCTOS -->
    @if($reparacion->reparacionProductos->count() > 0)
    <div class="section-title">Productos Utilizados</div>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th style="width:10%">Cant.</th>
                <th style="width:20%" class="text-right">Precio</th>
                <th style="width:20%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($reparacion->reparacionProductos as $rp)
            <tr>
                <td>{{ $rp->producto->nombre ?? '-' }}</td>
                <td>{{ $rp->cantidad }}</td>
                <td class="text-right">$ {{ number_format($rp->precio,2,',','.') }}</td>
                <td class="text-right">$ {{ number_format($rp->cantidad * $rp->precio,2,',','.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif

    <!-- TOTALES -->
    @if($total > 0)
    <table class="totales">
        @if($subtotalProductos > 0)
        <tr>
            <td>Subtotal Productos</td>
            <td class="text-right">$ {{ number_format($subtotalProductos,2,',','.') }}</td>
        </tr>
        @endif

        @if($subtotalServicios > 0)
        <tr>
            <td>Subtotal Servicios</td>
            <td class="text-right">$ {{ number_format($subtotalServicios,2,',','.') }}</td>
        </tr>
        @endif

        <tr class="total-final">
            <td>Total</td>
            <td class="text-right">$ {{ number_format($total,2,',','.') }}</td>
        </tr>
    </table>
    @endif

    <!-- FOOTER -->
    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }}<br>
        RDM - Reparaciones y Servicios Técnicos
    </div>

</div>
</body>
</html>
