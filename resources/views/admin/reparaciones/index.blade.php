<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Reparaciones</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">

    <!-- ================= MODAL ELIMINAR ================= -->
    <x-confirm-modal id="delete-reparacion-modal" title="¿Estás seguro?"
        message="¿Seguro que deseas eliminar esta reparación?" confirm-text="Eliminar" cancel-text="Cancelar" />

    <div class="flex min-h-screen">
        <x-admin-nav />

        <main class="flex-1 p-6">
            <div class="bg-white p-6 shadow">


                <!-- HEADER -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

                    <h1 class="text-2xl font-semibold">
                        Panel de Control de Reparaciones
                    </h1>

                    <div class="flex gap-3 items-center">
                        <a href="{{ route('reparaciones.create') }}"
                            class="px-4 py-2 border border-gray-300 hover:bg-gray-100">
                            <i class="fas fa-plus mr-2"></i> Nueva Reparación
                        </a>

                        <div class="flex border border-gray-300">
                            <button id="btn-table" class="px-4 py-2 bg-gray-900 text-white text-sm">
                                <i class="fas fa-list mr-1"></i> Tabla
                            </button>
                            <button id="btn-grid" class="px-4 py-2 text-sm hover:bg-gray-100">
                                <i class="fas fa-th mr-1"></i> Tarjetas
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ================= FILTROS ================= -->
                <form action="{{ route('reparaciones.index') }}" method="GET" class="mb-6 border-b pb-4">
                    <div class="flex flex-wrap gap-3 items-center">

                        <!-- Buscador -->
                        <input type="search" name="search" placeholder="Buscar por código, equipo o marca..."
                            value="{{ request('search') }}" class="flex-1 min-w-[220px] border px-3 py-2 shadow-sm">

                        <!-- Estado -->
                        <select name="estado_reparacion" class="border px-3 py-2">
                            <option value="">Estado</option>
                            @foreach (['Pendiente', 'En proceso', 'Reparado', 'No reparable', 'Entregado'] as $estado)
                                <option value="{{ $estado }}" @selected(request('estado_reparacion') === $estado)>
                                    {{ $estado }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Reparable -->
                        <select name="reparable" class="border px-3 py-2">
                            <option value="">Reparable</option>
                            <option value="1" @selected(request('reparable') === '1')>Sí</option>
                            <option value="0" @selected(request('reparable') === '0')>No</option>
                        </select>

                        <!-- Fecha -->
                        <input type="date" name="fecha_ingreso" value="{{ request('fecha_ingreso') }}"
                            class="border px-3 py-2">

                        <!-- Botones -->
                        <button type="submit" class="px-4 py-2 border hover:bg-gray-100">
                            <i class="fas fa-search mr-1"></i> Filtrar
                        </button>

                        <a href="{{ route('reparaciones.index') }}"
                            class="px-4 py-2 border border-gray-300 text-gray-600 hover:bg-gray-100">
                            Limpiar
                        </a>

                    </div>
                </form>



                <!-- ================= TABLA ================= -->
                <div id="table-view" class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Código</th>
                                <th class="px-4 py-2 text-left">Cliente</th>
                                <th class="px-4 py-2 text-left">Equipo</th>
                                <th class="px-4 py-2 text-left">Estado</th>
                                <th class="px-4 py-2 text-left">Ingreso</th>
                                <th class="px-4 py-2 text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach ($reparaciones as $reparacion)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 font-medium">
                                        {{ $reparacion->codigo_unico }}
                                    </td>

                                    <td class="px-4 py-2">
                                        {{ optional($reparacion->cliente)->NombreCompleto ?? 'Sin cliente' }}
                                    </td>

                                    <td class="px-4 py-2">
                                        {{ $reparacion->equipo_descripcion }} - {{ $reparacion->equipo_marca }}
                                    </td>

                                    <td class="px-4 py-2">
                                        {{ $reparacion->estado_reparacion }}
                                    </td>

                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($reparacion->fecha_ingreso)->format('d/m/Y') }}
                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        <div class="flex justify-center gap-3 text-gray-600">
                                            <a href="{{ route('reparaciones.show', $reparacion->id) }}"
                                                class="text-green-600" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="{{ route('reparaciones.edit', $reparacion->id) }}" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form id="delete-form-{{ $reparacion->id }}"
                                                action="{{ route('reparaciones.destroy', $reparacion->id) }}"
                                                method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <button type="button"
                                                onclick="openConfirmModal('delete-reparacion-modal', () => {
                                                    document.getElementById('delete-form-{{ $reparacion->id }}').submit();
                                                })"
                                                class="text-red-600 hover:text-red-800" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ================= GRID ================= -->
                <div id="grid-view" class="hidden">
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                        @foreach ($reparaciones as $reparacion)
                            <div class="border shadow-sm hover:shadow transition bg-white">
                                <div class="h-32 bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-tools text-4xl text-gray-400"></i>
                                </div>

                                <div class="p-4 text-sm space-y-2">
                                    <h3 class="font-semibold truncate">
                                        {{ $reparacion->codigo_unico }}
                                    </h3>

                                    <p class="text-gray-600 truncate">
                                        {{ optional($reparacion->cliente)->NombreCompleto ?? 'Sin cliente' }}
                                    </p>

                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $reparacion->equipo_descripcion }} - {{ $reparacion->equipo_marca }}
                                    </p>

                                    <p class="text-xs text-gray-600">
                                        Estado: {{ $reparacion->estado_reparacion }}
                                    </p>

                                    <p class="text-xs text-gray-600">
                                        Ingreso:
                                        {{ \Carbon\Carbon::parse($reparacion->fecha_ingreso)->format('d/m/Y') }}
                                    </p>

                                    <div class="flex gap-4 mt-3 text-gray-600">
                                        <a href="{{ route('reparaciones.show', $reparacion->id) }}"
                                            class="text-green-600" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('reparaciones.edit', $reparacion->id) }}" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button type="button"
                                            onclick="openConfirmModal('delete-reparacion-modal', () => {
                                                document.getElementById('delete-form-{{ $reparacion->id }}').submit();
                                            })"
                                            class="text-red-600 hover:text-red-800" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="mt-4">
                    {{ $reparaciones->links() }}
                </div>

            </div>
        </main>
    </div>

    <!-- ================= JS VIEW TOGGLE ================= -->
    <script>
        const btnTable = document.getElementById('btn-table');
        const btnGrid = document.getElementById('btn-grid');
        const tableView = document.getElementById('table-view');
        const gridView = document.getElementById('grid-view');

        function setView(view) {
            if (view === 'grid') {
                gridView.classList.remove('hidden');
                tableView.classList.add('hidden');
                btnGrid.classList.add('bg-gray-900', 'text-white');
                btnTable.classList.remove('bg-gray-900', 'text-white');
            } else {
                tableView.classList.remove('hidden');
                gridView.classList.add('hidden');
                btnTable.classList.add('bg-gray-900', 'text-white');
                btnGrid.classList.remove('bg-gray-900', 'text-white');
            }
        }

        btnTable.onclick = () => setView('table');
        btnGrid.onclick = () => setView('grid');

        setView('table');
    </script>

</body>

</html>
