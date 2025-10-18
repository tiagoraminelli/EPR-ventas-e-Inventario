<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Marca</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 p-8 font-sans">
    <div class="flex">
        <x-admin-nav />

    <div class="container mx-auto bg-white p-6 rounded-lg shadow-xl border border-gray-200 max-w-2xl mb-auto">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h1 class="text-3xl font-bold text-gray-800">Crear Nueva Marca</h1>
            <a href="{{ route('marcas.index') }}" class="text-blue-600 hover:text-blue-800 transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i> Volver al Dashboard
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-6 relative" role="alert">
                <strong class="font-bold">¡Error!</strong>
                <span class="block sm:inline">Hay algunos problemas con los datos proporcionados.</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="marcaForm" action="{{ route('marcas.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="nombre" class="block text-sm font-semibold text-gray-700">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                       required>
            </div>

            <div>
                <label for="descripcion" class="block text-sm font-semibold text-gray-700">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('descripcion') }}</textarea>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-bold rounded-md shadow-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i> Guardar Marca
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
