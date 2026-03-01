<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Servicios por Reparación</title>

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
                    Crear Servicios por Reparación
                </h1>

                <a href="{{ route('reparacionservicios.index') }}"
                   class="px-4 py-2 border border-gray-300 hover:bg-gray-100">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>

            <form action="{{ route('reparacionservicios.store') }}" method="POST" id="reparacionServiciosForm">
                @csrf

                <!-- ================= SELECCIÓN REPARACIÓN ================= -->
                <div class="mb-8">

                    <h3 class="text-sm font-semibold mb-4 text-gray-700 uppercase tracking-wide">
                        Seleccionar Reparación
                    </h3>

                    <div class="border border-gray-200 p-4 bg-gray-50">
                        <select name="reparacion_id"
                                id="reparacion_id"
                                required
                                class="w-full border border-gray-300 px-2 py-1">
                            <option value="">Seleccione una reparación...</option>
                            @foreach($reparaciones as $reparacion)
                                <option value="{{ $reparacion->id }}">
                                    #{{ $reparacion->id }} -
                                    {{ optional($reparacion->cliente)->NombreCompleto ?? 'Sin cliente' }}
                                    | {{ $reparacion->fecha_reparacion ?? '' }}
                                </option>
                            @endforeach
                        </select>
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
                        <i class="fas fa-save mr-1"></i> Guardar Servicios
                    </button>

                </div>

            </form>

        </div>
    </main>
</div>

<!-- ================= MODAL ELIMINAR ================= -->
<x-confirm-modal
    id="delete-service-modal"
    title="¿Eliminar servicio?"
    message="Esta acción eliminará el servicio agregado."
    confirm-text="Eliminar"
    cancel-text="Cancelar" />

<!-- ================= SCRIPTS ================= -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

$(document).ready(function () {

    let serviceIndex = 0;
    let rowToDelete = null;

    $('#reparacion_id').select2({ width: '100%' });

    function addServiceRow() {

        const row = `
        <tr class="hover:bg-gray-50 service-row">

            <td class="px-4 py-2">
                <select name="servicios[${serviceIndex}][servicio_id]"
                        class="service-select w-full border border-gray-300 px-2 py-1"
                        required>
                    <option value="">Seleccione...</option>
                    @foreach ($servicios as $servicio)
                        <option value="{{ $servicio->id }}"
                            data-precio="{{ $servicio->precio }}"
                            data-activo="{{ $servicio->activo ? 'Sí' : 'No' }}">
                            {{ $servicio->nombre }}
                        </option>
                    @endforeach
                </select>
            </td>

            <td class="px-4 py-2 text-center">
                <input type="number"
                       name="servicios[${serviceIndex}][cantidad]"
                       min="1"
                       required
                       class="cantidad-input w-20 border border-gray-300 px-2 py-1 text-center">
            </td>

            <td class="px-4 py-2 text-right">
                <input type="number"
                       step="0.01"
                       name="servicios[${serviceIndex}][precio]"
                       class="precio-input w-28 border border-gray-300 px-2 py-1 text-right">
            </td>

            <td class="px-4 py-2 text-right">
                <input type="text"
                       class="precio-servicio w-28 bg-gray-100 border border-gray-300 px-2 py-1 text-right"
                       disabled>
            </td>

            <td class="px-4 py-2 text-center">
                <input type="text"
                       class="activo-servicio w-16 bg-gray-100 border border-gray-300 px-2 py-1 text-center"
                       disabled>
            </td>

            <td class="px-4 py-2 text-right">
                <button type="button"
                        onclick="confirmDeleteService(this)"
                        class="text-gray-500 hover:text-red-600">
                    <i class="fas fa-trash"></i>
                </button>
            </td>

        </tr>`;

        $('#servicios-container').append(row);

        const select = $(`.service-select[name="servicios[${serviceIndex}][servicio_id]"]`);
        select.select2({ width: '100%' });

        select.on('change', function() {
            const selected = $(this).find(':selected');
            const precio = selected.data('precio') || '';
            const activo = selected.data('activo') || '';

            const row = $(this).closest('tr');
            row.find('.precio-servicio').val(precio);
            row.find('.activo-servicio').val(activo);
            row.find('.precio-input').val(precio);
        });

        serviceIndex++;
    }

    window.confirmDeleteService = function(button) {
        rowToDelete = $(button).closest('tr');

        openConfirmModal('delete-service-modal', function () {
            if (rowToDelete) {
                rowToDelete.remove();
                rowToDelete = null;
            }
        });
    };

    $('#add-service-btn').on('click', function () {
        addServiceRow();
    });

    addServiceRow();

});
</script>

</body>
</html>
