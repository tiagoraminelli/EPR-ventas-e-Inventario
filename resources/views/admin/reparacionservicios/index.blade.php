<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios por Reparación</title>

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
                    Panel de Servicios por Reparación
                </h1>

                <a href="{{ route('reparacionservicios.create') }}"
                   class="px-4 py-2 border border-gray-300 hover:bg-gray-100">
                    <i class="fas fa-plus mr-2"></i> Nuevo Registro
                </a>
            </div>

            <!-- ================= ALERTAS ================= -->
            @if (session('success'))
                <div class="mb-6 px-4 py-3 border border-green-300 bg-green-50 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 px-4 py-3 border border-red-300 bg-red-50 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- ================= FILTROS ================= -->
            <form method="GET" class="mb-6 flex flex-wrap gap-3 items-center">

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Buscar servicio..."
                       class="flex-grow border border-gray-300 px-3 py-2">

                <input type="text"
                       name="codigo_unico"
                       value="{{ request('codigo_unico') }}"
                       placeholder="Código único"
                       class="w-48 border border-gray-300 px-3 py-2">

                <select name="reparacion"
                        id="reparacion-select"
                        class="select2 w-56 border border-gray-300 px-3 py-2">
                    <option value="">Todas las reparaciones</option>
                    @foreach ($reparacionesFiltro as $repFiltro)
                        <option value="{{ $repFiltro->id }}"
                            {{ request('reparacion') == $repFiltro->id ? 'selected' : '' }}>
                            #{{ $repFiltro->id }} -
                            {{ optional($repFiltro->cliente)->NombreCompleto ?? 'Sin cliente' }}
                        </option>
                    @endforeach
                </select>

                <select name="servicios[]"
                        multiple
                        id="servicios-select"
                        class="select2 w-64 border border-gray-300 px-3 py-2">
                    @foreach ($serviciosFiltro as $servicio)
                        <option value="{{ $servicio->id }}"
                            @if(in_array($servicio->id, (array) request('servicios'))) selected @endif>
                            {{ $servicio->nombre }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="px-4 py-2 border border-gray-400 hover:bg-gray-100">
                    <i class="fas fa-search mr-1"></i> Buscar
                </button>
            </form>

            <!-- ================= TABLA ================= -->
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Reparación</th>
                            <th class="px-4 py-2 text-left">Equipo</th>
                            <th class="px-4 py-2 text-left">Cliente</th>
                            <th class="px-4 py-2 text-left">Servicios</th>
                            <th class="px-4 py-2 text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse ($reparaciones as $reparacion)
                            <tr class="hover:bg-gray-50">

                                <!-- Reparación -->
                                <td class="px-4 py-2">
                                    <div class="font-medium">
                                        #{{ $reparacion->id }}
                                    </div>
                                    <div class="text-gray-500 text-xs">
                                        {{ $reparacion->codigo_unico }}
                                    </div>
                                </td>

                                <!-- Equipo -->
                                <td class="px-4 py-2">
                                    {{ $reparacion->equipo_descripcion }}
                                </td>

                                <!-- Cliente -->
                                <td class="px-4 py-2">
                                    {{ optional($reparacion->cliente)->NombreCompleto ?? 'Sin cliente' }}
                                </td>

                                <!-- Servicios -->
                                <td class="px-4 py-2">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($reparacion->reparacionServicios as $item)
                                            <div class="border border-gray-200 px-2 py-1 text-xs bg-gray-50">
                                                {{ optional($item->servicio)->nombre ?? 'N/A' }}
                                                (x{{ $item->cantidad ?? 1 }})
                                                - ${{ number_format($item->precio ?? 0, 2, ',', '.') }}
                                            </div>
                                        @endforeach
                                    </div>
                                </td>

                                <!-- Acciones -->
                                <td class="px-4 py-2 text-right">
                                    <div class="flex justify-end gap-3 text-gray-600">
                                        <a href="{{ route('reparacionservicios.edit', $reparacion) }}"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button onclick="openDeleteModal(this)"
                                                data-url="{{ route('reparacionservicios.destroy', $reparacion) }}"
                                                class="text-red-600"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    No se encontraron reparaciones con servicios.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ================= PAGINACIÓN ================= -->
            <div class="mt-4">
                {{ $reparaciones->links() }}
            </div>

        </div>
    </main>
</div>

<!-- ================= MODAL ELIMINAR ================= -->
<x-confirm-modal
    id="delete-reparacion-modal"
    title="¿Estás seguro?"
    message="Esta acción eliminará el registro."
    confirm-text="Eliminar"
    cancel-text="Cancelar" />

<!-- ================= SCRIPTS ================= -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Seleccionar',
            allowClear: true,
            width: 'resolve'
        });
    });

    let deleteUrl = null;

    function openDeleteModal(button) {
        deleteUrl = button.getAttribute('data-url');

        openConfirmModal('delete-reparacion-modal', function () {
            executeDelete();
        });
    }

    function executeDelete() {
        if (!deleteUrl) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]').content;

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';

        form.appendChild(csrf);
        form.appendChild(method);

        document.body.appendChild(form);
        form.submit();
    }
</script>

</body>
</html>
