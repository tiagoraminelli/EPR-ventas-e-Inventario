<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Marca</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <x-admin-nav />

    <div class="container mx-auto bg-white p-6 rounded-lg shadow-lg mt-10 mb-auto" @style("max-width: 800px")>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Editar Marca: {{ $marca->nombre }}</h1>
            <a href="{{ route('marcas.index') }}" class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i> Volver al Dashboard
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4">
                <strong class="font-bold">¡Error!</strong>
                <span class="block sm:inline">Hay algunos problemas con los datos proporcionados.</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('marcas.update', $marca->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $marca->nombre) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
            </div>

            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('descripcion', $marca->descripcion) }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out">
                    <i class="fas fa-save mr-2"></i> Actualizar Marca
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
