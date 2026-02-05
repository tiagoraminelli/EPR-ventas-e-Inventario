<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Clientes</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <div class="flex-shrink-0 w-64 bg-white shadow-lg">
            <x-admin-nav />
        </div>

        <!-- Contenido -->
        <main class="flex-1 p-6">

            <div class="bg-white p-6 shadow-lg">

                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">
                        Panel de Clientes
                    </h1>

                    <div class="flex items-center gap-3">
                        <div class="flex border border-gray-300">
                            <button id="btn-table" class="px-4 py-2 text-sm">
                                <i class="fas fa-list mr-1"></i> Tabla
                            </button>
                            <button id="btn-grid" class="px-4 py-2 text-sm hover:bg-gray-100">
                                <i class="fas fa-th mr-1"></i> Imágenes
                            </button>
                        </div>

                        <a href="{{ route('clientes.create') }}"
                            class="px-4 py-2 border border-gray-300 hover:bg-gray-100">
                            <i class="fas fa-plus mr-2"></i> Nuevo Cliente
                        </a>
                    </div>
                </div>

                <!-- Buscador -->
                <div class="mb-6 border-b pb-4">
                    <form action="{{ route('clientes.index') }}" method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buscar por nombre, razón social, CUIT/DNI o email..."
                            class="flex-1 border shadow-sm px-3 py-2">

                        <button type="submit" class="px-4 py-2 border border-gray-400 hover:bg-gray-100">
                            <i class="fas fa-search mr-1"></i> Buscar
                        </button>
                    </form>
                </div>

                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- ================= TABLA ================= -->
                <div id="table-view" class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs uppercase">Nombre</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Razón Social</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">CUIT/DNI</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Tipo</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Email</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Detalle</th>
                                <th class="px-4 py-2 text-center text-xs uppercase">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach ($clientes as $cliente)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm">
                                        {{ \Illuminate\Support\Str::limit($cliente->NombreCompleto, 20) }}
                                    </td>
                                    <td class="px-4 py-2 text-sm">{{ $cliente->RazonSocial }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $cliente->cuit_dni }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $cliente->TipoCliente }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $cliente->Email }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $cliente->Detalle }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex justify-center gap-3">

                                            <!-- Facturas / Ventas -->
                                            <a href="{{ route('ventas.index', [
                                                'search' => '',
                                                'cliente_id' => $cliente->id,
                                                'estado_venta' => '',
                                                'fecha_venta' => '',
                                                'fecha_venta_exacta' => '',
                                                'condicion_pago' => '',
                                                'tipo_comprobante' => '',
                                            ]) }}"
                                                class="text-blue-600 hover:underline">
                                                Ver C/C
                                            </a>


                                            <a href="{{ route('clientes.edit', $cliente->id) }}">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </a>

                                            <button type="button" class="text-red-600"
                                                onclick="openConfirmModal('delete-client-modal', () => {
                                            document.getElementById('delete-form-{{ $cliente->id }}').submit();
                                        })">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>

                                            <a href="https://wa.me/{{ $cliente->Telefono }}" target="_blank">
                                                <i class="fa-brands fa-whatsapp text-green-600"></i>
                                            </a>



                                            <form id="delete-form-{{ $cliente->id }}"
                                                action="{{ route('clientes.destroy', $cliente->id) }}" method="POST"
                                                class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>

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

                        @foreach ($clientes as $cliente)
                            @php
                                $imagenes = [
                                    'Empresa' => 'clientes/empresa.png',
                                    'Institucion Publica' => 'clientes/instituto.png',
                                    'Persona' => 'clientes/persona.png',
                                ];
                                $img = $imagenes[$cliente->TipoCliente] ?? 'clientes/persona.png';
                            @endphp

                            <div class="border shadow-sm hover:shadow transition bg-white flex flex-col">

                                <div class="h-36 bg-gray-100 overflow-hidden">
                                    <img src="{{ asset('storage/' . $img) }}" class="h-full w-full object-cover">
                                </div>

                                <div class="p-4 text-sm flex flex-col flex-1">
                                    <h3 class="font-semibold truncate">{{ $cliente->NombreCompleto }}</h3>
                                    <p class="text-gray-500 truncate">{{ $cliente->RazonSocial ?? '—' }}</p>
                                    <p class="text-xs text-gray-600 mt-1">{{ $cliente->TipoCliente }}</p>

                                    <div class="flex justify-between items-center pt-3 mt-auto border-t">

                                        <a href="{{ route('clientes.edit', $cliente->id) }}">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>

                                        <button type="button" class="text-red-600"
                                            onclick="openConfirmModal('delete-client-modal', () => {
                                            document.getElementById('delete-form-{{ $cliente->id }}').submit();
                                        })">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>

                                        <a href="https://wa.me/{{ $cliente->Telefono }}" target="_blank">
                                            <i class="fa-brands fa-whatsapp text-green-600"></i>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="mt-6">
                    {{ $clientes->appends(request()->query())->links() }}
                </div>

            </div>
        </main>
    </div>

    <!-- ================= MODAL COMPONENT ================= -->
    <x-confirm-modal id="delete-client-modal" title="¿Estás seguro?" message="¿Seguro que deseas eliminar este cliente?"
        confirm-text="Eliminar" cancel-text="Cancelar" />

    <!-- ================= JS VIEW TOGGLE ================= -->
    <script>
        const tableView = document.getElementById('table-view');
        const gridView = document.getElementById('grid-view');
        const btnTable = document.getElementById('btn-table');
        const btnGrid = document.getElementById('btn-grid');
        const VIEW_KEY = 'clientes_view';

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
            localStorage.setItem(VIEW_KEY, view);
        }

        btnTable.onclick = () => setView('table');
        btnGrid.onclick = () => setView('grid');

        document.addEventListener('DOMContentLoaded', () => {
            setView(localStorage.getItem(VIEW_KEY) || 'table');
        });
    </script>

</body>

</html>
