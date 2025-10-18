<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reparación</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 42px;
            display: flex;
            align-items: center;
            border-radius: 0.375rem;
            border-color: #d1d5db;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-gray-100 p-4 font-sans">

    <div class="flex min-h-screen">
        <x-admin-nav />

        <div class="flex-1 container mx-auto bg-white p-8 rounded-xl shadow-lg">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h1 class="text-3xl font-bold text-gray-800">Editar Reparación</h1>
                <a href="{{ route('reparaciones.index') }}"
                    class="px-4 py-2 text-blue-600 bg-blue-100 rounded-lg font-semibold hover:bg-blue-200 transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al listado
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                    <p class="font-bold">¡Error!</p>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('reparaciones.update', $reparacion->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="codigo_unico" class="block text-sm font-semibold text-gray-700 mb-1">Código Único</label>
                        <input type="text" name="codigo_unico" id="codigo_unico"
                            value="{{ old('codigo_unico', $reparacion->codigo_unico) }}"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                    </div>
                    <div>
                        <label for="cliente_id" class="block text-sm font-semibold text-gray-700 mb-1">Cliente</label>
                        <select name="cliente_id" id="cliente_id" class="select2 w-full"
                            required>
                            <option value="">Selecciona un cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}"
                                    {{ old('cliente_id', $reparacion->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->NombreCompleto }} | {{ $cliente->RazonSocial }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="equipo_descripcion" class="block text-sm font-semibold text-gray-700 mb-1">Descripción del Equipo</label>
                    <input type="text" name="equipo_descripcion" id="equipo_descripcion"
                        value="{{ old('equipo_descripcion', $reparacion->equipo_descripcion) }}"
                        class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                        required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="equipo_marca" class="block text-sm font-semibold text-gray-700 mb-1">Marca</label>
                        <input type="text" name="equipo_marca" id="equipo_marca"
                            value="{{ old('equipo_marca', $reparacion->equipo_marca) }}"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                    </div>
                    <div>
                        <label for="equipo_modelo" class="block text-sm font-semibold text-gray-700 mb-1">Modelo</label>
                        <input type="text" name="equipo_modelo" id="equipo_modelo"
                            value="{{ old('equipo_modelo', $reparacion->equipo_modelo) }}"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                    </div>
                    <div>
                        <label for="reparable" class="block text-sm font-semibold text-gray-700 mb-1">Reparable</label>
                        <select name="reparable" id="reparable"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                            <option value="1" {{ old('reparable', $reparacion->reparable) == 1 ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ old('reparable', $reparacion->reparable) == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="descripcion_danio" class="block text-sm font-semibold text-gray-700 mb-1">Descripción del Daño</label>
                    <textarea name="descripcion_danio" id="descripcion_danio" rows="3"
                        class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                        required>{{ old('descripcion_danio', $reparacion->descripcion_danio) }}</textarea>
                </div>

                <div>
                    <label for="solucion_aplicada" class="block text-sm font-semibold text-gray-700 mb-1">Solución Aplicada</label>
                    <textarea name="solucion_aplicada" id="solucion_aplicada" rows="3"
                        class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">{{ old('solucion_aplicada', $reparacion->solucion_aplicada) }}</textarea>
                </div>

                <div>
                    <label for="estado_reparacion" class="block text-sm font-semibold text-gray-700 mb-1">Estado</label>
                    <input type="text" name="estado_reparacion" id="estado_reparacion"
                        value="{{ old('estado_reparacion', $reparacion->estado_reparacion) }}"
                        class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                        required>
                </div>

                <div>
                    <label for="fecha_ingreso" class="block text-sm font-semibold text-gray-700 mb-1">Fecha de Ingreso</label>
                    <input type="datetime-local" name="fecha_ingreso" id="fecha_ingreso"
                        value="{{ old('fecha_ingreso', \Carbon\Carbon::parse($reparacion->fecha_ingreso)->format('Y-m-d\TH:i')) }}"
                        class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                        required>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="px-8 py-3 bg-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i> Actualizar Reparación
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Selecciona un cliente",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</body>
</html>
