<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex">
        <x-admin-nav />

    <div class="container mx-auto bg-white p-6 rounded-lg shadow-lg mt-10 mb-10">
        <!-- Encabezado y botón de Volver -->
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h1 class="text-3xl font-bold text-gray-800">Editar Cliente: {{ $cliente->cuit_dni }}</h1>
            <a href="{{ route('clientes.index') }}" class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Volver al Listado
            </a>
        </div>

        <!-- Sección de Errores -->
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

        <!-- Formulario de Edición -->
        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Sección de Información General -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tipo de Cliente (Select) -->
                <div>
                    <label for="TipoCliente" class="block text-sm font-medium text-gray-700">Tipo de Cliente</label>
                    <select name="TipoCliente" id="TipoCliente" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        <option value="Persona" {{ old('TipoCliente', $cliente->TipoCliente) == 'Persona' ? 'selected' : '' }}>Persona</option>
                        <option value="Empresa" {{ old('TipoCliente', $cliente->TipoCliente) == 'Empresa' ? 'selected' : '' }}>Empresa</option>
                    </select>
                </div>

                <!-- CUIT/DNI (requerido) -->
                <div>
                    <label for="cuit_dni" class="block text-sm font-medium text-gray-700">CUIT/DNI</label>
                    <input type="text" name="cuit_dni" id="cuit_dni" value="{{ old('cuit_dni', $cliente->cuit_dni) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                </div>

                <!-- Razón Social -->
                <div>
                    <label for="RazonSocial" class="block text-sm font-medium text-gray-700">Razón Social</label>
                    <input type="text" name="RazonSocial" id="RazonSocial" value="{{ old('RazonSocial', $cliente->RazonSocial) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                <!-- Nombre Completo -->
                <div>
                    <label for="NombreCompleto" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                    <input type="text" name="NombreCompleto" id="NombreCompleto" value="{{ old('NombreCompleto', $cliente->NombreCompleto) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
            </div>

            <!-- Sección de Contacto y Ubicación -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Teléfono -->
                <div>
                    <label for="Telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="Telefono" id="Telefono" value="{{ old('Telefono', $cliente->Telefono) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                <!-- Email (requerido) -->
                <div>
                    <label for="Email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="Email" id="Email" value="{{ old('Email', $cliente->Email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                </div>

                <!-- Domicilio -->
                <div>
                    <label for="Domicilio" class="block text-sm font-medium text-gray-700">Domicilio</label>
                    <input type="text" name="Domicilio" id="Domicilio" value="{{ old('Domicilio', $cliente->Domicilio) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                <!-- Localidad -->
                <div>
                    <label for="Localidad" class="block text-sm font-medium text-gray-700">Localidad</label>
                    <input type="text" name="Localidad" id="Localidad" value="{{ old('Localidad', $cliente->Localidad) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
            </div>

            <!-- Sección de Detalle y Visibilidad -->
            <div>
                <!-- Detalle (Textarea) -->
                <div class="mb-4">
                    <label for="Detalle" class="block text-sm font-medium text-gray-700">Detalle</label>
                    <textarea name="Detalle" id="Detalle" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('Detalle', $cliente->Detalle) }}</textarea>
                </div>

                <!-- Visible (Checkbox) -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="visible" name="visible" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ old('visible', $cliente->visible) ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="visible" class="font-medium text-gray-700">Visible</label>
                        <p class="text-gray-500">Determina si este cliente está activo y visible.</p>
                    </div>
                </div>
            </div>

            <!-- Botón de Actualizar -->
            <div class="flex justify-end pt-6 border-t mt-6">
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out flex items-center">
                    <i class="fas fa-save mr-2"></i> Actualizar Cliente
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
