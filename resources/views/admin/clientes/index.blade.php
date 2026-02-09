<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Clientes</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">

    <!-- ================= MODAL ELIMINAR ================= -->
    <x-confirm-modal
        id="delete-client-modal"
        title="¿Estás seguro?"
        message="¿Seguro que deseas eliminar este cliente?"
        confirm-text="Eliminar"
        cancel-text="Cancelar"
    />

    <div class="flex min-h-screen">
        <x-admin-nav />

        <main class="flex-1 p-6">
            <div class="bg-white p-6 shadow">

                <!-- HEADER -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h1 class="text-2xl font-semibold">
                        Panel de Control de Clientes
                    </h1>

                    <div class="flex gap-3 items-center">
                        <a href="{{ route('clientes.create') }}"
                           class="px-4 py-2 border border-gray-300 hover:bg-gray-100">
                            <i class="fas fa-plus mr-2"></i> Nuevo Cliente
                        </a>

                        <div class="flex border border-gray-300">
                            <button id="btn-table" class="px-4 py-2 bg-gray-900 text-white text-sm">
                                <i class="fas fa-list mr-1"></i> Tabla
                            </button>
                            <button id="btn-grid" class="px-4 py-2 text-sm hover:bg-gray-100">
                                <i class="fas fa-th mr-1"></i> Imágenes
                            </button>
                        </div>
                    </div>
                </div>

                <!-- BUSCADOR -->
                <form method="GET" class="mb-6 flex gap-3 items-center">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar cliente..."
                        class="flex-grow border border-gray-300 px-3 py-2"
                    >

                    <button type="submit" class="px-4 py-2 border border-gray-400 hover:bg-gray-100">
                        <i class="fas fa-search mr-1"></i> Buscar
                    </button>
                </form>

                <!-- ================= TABLA ================= -->
                <div id="table-view" class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Nombre</th>
                                <th class="px-4 py-2 text-left">Razón Social</th>
                                <th class="px-4 py-2 text-left">CUIT/DNI</th>
                                <th class="px-4 py-2 text-left">Tipo</th>
                                <th class="px-4 py-2 text-left">Email</th>
                                <th class="px-4 py-2 text-left">Detalle</th>
                                <th class="px-4 py-2 text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach ($clientes as $cliente)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        {{ \Illuminate\Support\Str::limit($cliente->NombreCompleto, 20) }}
                                    </td>
                                    <td class="px-4 py-2">{{ $cliente->RazonSocial }}</td>
                                    <td class="px-4 py-2">{{ $cliente->cuit_dni }}</td>
                                    <td class="px-4 py-2">{{ $cliente->TipoCliente }}</td>
                                    <td class="px-4 py-2">{{ $cliente->Email }}</td>
                                    <td class="px-4 py-2">{{ $cliente->Detalle }}</td>

                                    <td class="px-4 py-2 text-center">
                                        <div class="flex justify-center gap-3 text-gray-600">

                                            <!-- Ver cuenta corriente -->
                                            <a href="{{ route('ventas.index', ['cliente_id' => $cliente->id]) }}"
                                               title="Ver cuenta corriente"
                                               class="text-green-600">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="{{ route('clientes.edit', $cliente->id) }}" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form id="delete-form-{{ $cliente->id }}"
                                                  action="{{ route('clientes.destroy', $cliente->id) }}"
                                                  method="POST"
                                                  class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <button
                                                type="button"
                                                onclick="openConfirmModal('delete-client-modal', () => {
                                                    document.getElementById('delete-form-{{ $cliente->id }}').submit();
                                                })"
                                                class="text-red-600 hover:text-red-800"
                                                title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                            <a href="https://wa.me/{{ $cliente->Telefono }}" target="_blank">
                                                <i class="fab fa-whatsapp text-green-600"></i>
                                            </a>
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

                            <div class="border shadow-sm hover:shadow transition bg-white">
                                <div class="h-40 bg-gray-100 overflow-hidden">
                                    <img src="{{ asset('storage/' . $img) }}"
                                         class="object-cover h-full w-full">
                                </div>

                                <div class="p-4 text-sm space-y-2">
                                    <h3 class="font-semibold truncate">{{ $cliente->NombreCompleto }}</h3>
                                    <p class="text-gray-500 truncate">{{ $cliente->RazonSocial ?? '—' }}</p>
                                    <p class="text-xs text-gray-600">{{ $cliente->TipoCliente }}</p>

                                    <div class="flex gap-4 mt-3 text-gray-600">
                                        <a href="{{ route('clientes.edit', $cliente->id) }}" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button
                                            type="button"
                                            onclick="openConfirmModal('delete-client-modal', () => {
                                                document.getElementById('delete-form-{{ $cliente->id }}').submit();
                                            })"
                                            class="text-red-600 hover:text-red-800"
                                            title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        <a href="https://wa.me/{{ $cliente->Telefono }}" target="_blank">
                                            <i class="fab fa-whatsapp text-green-600"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="mt-4">
                    {{ $clientes->appends(request()->query())->links() }}
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
