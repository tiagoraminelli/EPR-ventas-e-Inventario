<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reparaciones</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-indigo-100 font-sans">
<div class="flex min-h-screen">
    <x-admin-nav />

    <main class="flex-1 p-6">
        <div class="bg-white p-6 rounded-lg shadow-lg">

            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                Gestión de Reparaciones
            </h1>

            <!-- TABLA -->
            <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Equipo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ingreso</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                    </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($reparaciones as $reparacion)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm">
                                {{ $reparacion->codigo_unico }}
                            </td>

                            <td class="px-6 py-4 text-sm">
                                {{ optional($reparacion->cliente)->NombreCompleto ?? 'Sin cliente' }}
                            </td>

                            <td class="px-6 py-4 text-sm">
                                {{ $reparacion->equipo_descripcion }} - {{ $reparacion->equipo_marca }}
                            </td>

                            <td class="px-6 py-4 text-sm">
                                {{ $reparacion->estado_reparacion }}
                            </td>

                            <td class="px-6 py-4 text-sm">
                                {{ \Carbon\Carbon::parse($reparacion->fecha_ingreso)->format('d/m/Y') }}
                            </td>

                            <td class="px-6 py-4 text-right text-sm">
                                <a href="{{ route('reparaciones.show', $reparacion->id) }}" class="text-green-600 mr-2">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('reparaciones.edit', $reparacion->id) }}" class="text-indigo-600 mr-2">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- ELIMINAR -->
                                <button type="button"
                                    onclick="openConfirmModal('delete-reparacion-modal', () => {
                                        document.getElementById('delete-form-{{ $reparacion->id }}').submit();
                                    })"
                                    class="text-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <form id="delete-form-{{ $reparacion->id }}"
                                      action="{{ route('reparaciones.destroy', $reparacion->id) }}"
                                      method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </main>
</div>

<!-- COMPONENTE MODAL -->
<x-confirm-modal
    id="delete-reparacion-modal"
    title="¿Eliminar reparación?"
    message="Esta acción eliminará la reparación de forma permanente."
    confirm-text="Eliminar"
    cancel-text="Cancelar"
/>


</body>
</html>
