<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Servicios por Reparación</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">
    <x-admin-nav />

    <main class="flex-1 p-6">
        <div class="bg-white p-6 shadow">

            <!-- ================= HEADER ================= -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h1 class="text-2xl font-semibold">
                    Editar Servicios de Reparación #{{ $reparacion->id }}
                </h1>

                <a href="{{ route('reparacionservicios.index') }}"
                   class="px-4 py-2 border border-gray-300 hover:bg-gray-100">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>

            <form action="{{ route('reparacionservicios.update', $reparacion) }}"
                  method="POST"
                  id="editReparacionServiciosForm">
                @csrf
                @method('PUT')

                <!-- ================= INFO REPARACIÓN ================= -->
                <div class="grid md:grid-cols-2 gap-6 mb-8">

                    <div class="border border-gray-200 p-4 bg-gray-50">
                        <h3 class="text-sm font-semibold mb-3 text-gray-700 uppercase tracking-wide">
                            Información de Reparación
                        </h3>

                        <div class="text-sm space-y-1 text-gray-700">
                            <p><span class="font-medium">Fecha:</span>
                                {{ \Carbon\Carbon::parse($reparacion->fecha_ingreso)->format('d/m/Y') }}</p>
                            <p><span class="font-medium">Estado:</span> {{ $reparacion->estado }}</p>
                            <p><span class="font-medium">Código Único:</span> {{ $reparacion->codigo_unico }}</p>
                            <p><span class="font-medium">Equipo:</span> {{ $reparacion->equipo_descripcion }}</p>
                        </div>
                    </div>

                    <div class="border border-gray-200 p-4 bg-gray-50">
                        <h3 class="text-sm font-semibold mb-3 text-gray-700 uppercase tracking-wide">
                            Cliente
                        </h3>

                        <div class="text-sm space-y-1 text-gray-700">
                            <p><span class="font-medium">Nombre:</span>
                                {{ optional($reparacion->cliente)->NombreCompleto ?? 'Sin dato' }}</p>
                            <p><span class="font-medium">CUIT/DNI:</span>
                                {{ optional($reparacion->cliente)->cuit_dni ?? 'Sin dato' }}</p>
                            <p><span class="font-medium">Teléfono:</span>
                                {{ optional($reparacion->cliente)->Telefono ?? '-' }}</p>
                            <p><span class="font-medium">Localidad:</span>
                                {{ optional($reparacion->cliente)->Localidad ?? '-' }}</p>
                        </div>
                    </div>

                </div>

                <!-- ================= DETALLE SERVICIOS ================= -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold mb-4 text-gray-700 uppercase tracking-wide">
                        Detalle de Servicios
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Servicio</th>
                                    <th class="px-4 py-2 text-center">Cantidad</th>
                                    <th class="px-4 py-2 text-right">Precio Unit.</th>
                                    <th class="px-4 py-2 text-right">Precio Ref.</th>
                                    <th class="px-4 py-2 text-center">Activo</th>
                                    <th class="px-4 py-2 text-right">Acción</th>
                                </tr>
                            </thead>

                            <tbody id="servicios-container" class="divide-y">
                                @foreach ($reparacion->reparacionServicios as $index => $rs)
                                    <tr class="hover:bg-gray-50 service-row">
                                        <input type="hidden"
                                               name="servicios[{{ $index }}][id]"
                                               value="{{ $rs->id }}">

                                        <td class="px-4 py-2">
                                            <select name="servicios[{{ $index }}][servicio_id]"
                                                    class="service-select w-full border border-gray-300 px-2 py-1"
                                                    required>
                                                @foreach ($servicios as $servicio)
                                                    <option value="{{ $servicio->id }}"
                                                        data-precio="{{ $servicio->precio }}"
                                                        data-activo="{{ $servicio->activo ? 'Sí' : 'No' }}"
                                                        @if ($rs->servicio_id == $servicio->id) selected @endif>
                                                        {{ $servicio->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td class="px-4 py-2 text-center">
                                            <input type="number"
                                                   name="servicios[{{ $index }}][cantidad]"
                                                   value="{{ $rs->cantidad }}"
                                                   min="1"
                                                   required
                                                   class="cantidad-input w-20 border border-gray-300 px-2 py-1 text-center">
                                        </td>

                                        <td class="px-4 py-2 text-right">
                                            <input type="number"
                                                   step="0.01"
                                                   name="servicios[{{ $index }}][precio]"
                                                   value="{{ $rs->precio }}"
                                                   class="precio-input w-28 border border-gray-300 px-2 py-1 text-right">
                                        </td>

                                        <td class="px-4 py-2 text-right">
                                            <input type="text"
                                                   class="precio-servicio w-28 bg-gray-100 border border-gray-300 px-2 py-1 text-right"
                                                   value="{{ $rs->servicio->precio }}"
                                                   disabled>
                                        </td>

                                        <td class="px-4 py-2 text-center">
                                            <input type="text"
                                                   class="activo-servicio w-16 bg-gray-100 border border-gray-300 px-2 py-1 text-center"
                                                   value="{{ $rs->servicio->activo ? 'Sí' : 'No' }}"
                                                   disabled>
                                        </td>

                                        <td class="px-4 py-2 text-right">
                                            <button type="button"
                                                    class="remove-service-btn text-gray-500 hover:text-red-600">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= ACCIONES ================= -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 border-t pt-6">

                    <button type="button"
                            id="add-service-btn"
                            class="px-4 py-2 border border-gray-400 hover:bg-gray-100">
                        <i class="fas fa-plus mr-1"></i> Agregar Servicio
                    </button>

                    <button type="submit"
                            class="px-6 py-2 bg-gray-900 text-white hover:bg-gray-800">
                        <i class="fas fa-save mr-1"></i> Guardar Cambios
                    </button>

                </div>

            </form>

        </div>
    </main>
</div>
