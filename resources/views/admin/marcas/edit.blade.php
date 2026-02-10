<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Marca</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200">
            <x-admin-nav />
        </aside>

        <!-- Contenido -->
        <main class="flex-1 p-6">
            <div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow">

                <!-- Header -->
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h1 class="text-2xl font-semibold text-gray-800">
                        Editar Marca
                    </h1>

                    <a href="{{ route('marcas.index') }}"
                        class="text-sm text-gray-600 hover:text-gray-900 transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Volver
                    </a>
                </div>

                <!-- Errores -->
                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <p class="font-semibold mb-2">Hay errores en el formulario:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('marcas.update', $marca->id) }}"
                      method="POST"
                      class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre
                        </label>
                        <input type="text"
                               name="nombre"
                               id="nombre"
                               value="{{ old('nombre', $marca->nombre) }}"
                               required
                               class="w-full rounded-lg border-gray-300 text-sm
                                      focus:ring-gray-900 focus:border-gray-900">
                    </div>

                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">
                            Descripción
                        </label>
                        <textarea name="descripcion"
                                  id="descripcion"
                                  rows="3"
                                  class="w-full rounded-lg border-gray-300 text-sm
                                         focus:ring-gray-900 focus:border-gray-900">{{ old('descripcion', $marca->descripcion) }}</textarea>
                    </div>

                    <!-- Acciones -->
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('marcas.index') }}"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-sm
                                   text-gray-700 hover:bg-gray-100 transition">
                            Cancelar
                        </a>

                        <button type="submit"
                            class="px-5 py-2 rounded-lg bg-gray-900 text-white text-sm
                                   font-medium hover:bg-gray-800 transition flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Actualizar Marca
                        </button>
                    </div>

                </form>

            </div>
        </main>

    </div>

</body>
</html>
