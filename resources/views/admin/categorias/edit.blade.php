<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <x-admin-nav />

    <div class="container mx-auto bg-white p-6 rounded-lg shadow-lg mt-10 mb-auto" style="max-width: 600px">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Editar Categoría: {{ $categoria->nombre }}</h1>
            <a href="{{ route('categorias.index') }}" class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i> Volver al Dashboard
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('categorias.update', $categoria->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 font-semibold mb-2">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $categoria->nombre) }}"
                       class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-4">
                <label for="descripcion" class="block text-gray-700 font-semibold mb-2">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="4"
                          class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('descripcion', $categoria->descripcion) }}</textarea>
            </div>

            <div class="flex space-x-2">
                <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                    <i class="fas fa-save mr-2"></i> Guardar Cambios
                </button>
                <a href="{{ route('categorias.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
