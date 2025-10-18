<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Servicio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .modal {
            transition: opacity 0.25s ease-in-out;
        }
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
                <h1 class="text-3xl font-bold text-gray-800">Crear Nuevo Servicio</h1>
                <a href="{{ route('servicios.index') }}"
                    class="px-4 py-2 text-blue-600 bg-blue-100 rounded-lg font-semibold hover:bg-blue-200 transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Dashboard
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

            <form action="{{ route('servicios.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                    </div>
                    <div>
                        <label for="iva_aplicable" class="block text-sm font-semibold text-gray-700 mb-1">IVA Aplicable</label>
                        <select name="iva_aplicable" id="iva_aplicable"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                            <option value="21" {{ old('iva_aplicable') == '21' ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ old('iva_aplicable') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3"
                        class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">{{ old('descripcion') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="precio" class="block text-sm font-semibold text-gray-700 mb-1">Precio ($)</label>
                        <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio') }}"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                    </div>
                    <div>
                        <label for="activo" class="block text-sm font-semibold text-gray-700 mb-1">Activo</label>
                        <select name="activo" id="activo"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                            <option value="1" {{ old('activo') == '1' ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ old('activo') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div>
                        <label for="visible" class="block text-sm font-semibold text-gray-700 mb-1">Visible</label>
                        <select name="visible" id="visible"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                            <option value="1" {{ old('visible') == '1' ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ old('visible') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="px-8 py-3 bg-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i> Guardar Servicio
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
