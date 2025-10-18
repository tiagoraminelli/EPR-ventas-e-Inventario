<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cliente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <!-- Navbar / Sidebar -->
        <x-admin-nav />

        <!-- Contenido principal -->
        <main class="flex-1 p-8">
            <div class="container mx-auto bg-white p-6 rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h1 class="text-3xl font-bold text-gray-800">Crear Nuevo Cliente</h1>
                    <a href="{{ route('clientes.index') }}" class="text-blue-600 hover:text-blue-800 transition duration-300">
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

                <form id="clienteForm" action="{{ route('clientes.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Razón Social -->
                        <div>
                            <label for="RazonSocial" class="block text-sm font-semibold text-gray-700">Razón Social</label>
                            <input type="text" name="RazonSocial" id="RazonSocial" value="{{ old('RazonSocial') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>

                        <!-- Nombre Completo -->
                        <div>
                            <label for="NombreCompleto" class="block text-sm font-semibold text-gray-700">Nombre Completo</label>
                            <input type="text" name="NombreCompleto" id="NombreCompleto" value="{{ old('NombreCompleto') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>

                        <!-- CUIT/DNI -->
                        <div>
                            <label for="cuit_dni" class="block text-sm font-semibold text-gray-700">CUIT / DNI</label>
                            <input type="text" name="cuit_dni" id="cuit_dni" value="{{ old('cuit_dni') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                   required>
                        </div>

                        <!-- Domicilio -->
                        <div>
                            <label for="Domicilio" class="block text-sm font-semibold text-gray-700">Domicilio</label>
                            <input type="text" name="Domicilio" id="Domicilio" value="{{ old('Domicilio') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>

                        <!-- Localidad -->
                        <div>
                            <label for="Localidad" class="block text-sm font-semibold text-gray-700">Localidad</label>
                            <input type="text" name="Localidad" id="Localidad" value="{{ old('Localidad') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="Email" class="block text-sm font-semibold text-gray-700">Email</label>
                            <input type="email" name="Email" id="Email" value="{{ old('Email') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                   required>
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="Telefono" class="block text-sm font-semibold text-gray-700">Teléfono</label>
                            <input type="text" name="Telefono" id="Telefono" value="{{ old('Telefono') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>

                        <!-- Tipo de Cliente (Select) -->
                        <div>
                            <label for="TipoCliente" class="block text-sm font-semibold text-gray-700">Tipo de Cliente</label>
                            <select name="TipoCliente" id="TipoCliente" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="Persona" {{ old('TipoCliente') == 'Persona' ? 'selected' : '' }}>Persona</option>
                                <option value="Empresa" {{ old('TipoCliente') == 'Empresa' ? 'selected' : '' }}>Empresa</option>
                                <option value="Institución publica" {{ old('TipoCliente') == 'Institución publica' ? 'selected' : '' }}>Institución publica</option>
                            </select>
                        </div>

                        <!-- Detalle (Select) -->
                        <div>
                            <label for="Detalle" class="block text-sm font-semibold text-gray-700">Detalle</label>
                            <select name="Detalle" id="Detalle"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="MONOTRIBUTISTA" {{ old('Detalle') == 'MONOTRIBUTISTA' ? 'selected' : '' }}>MONOTRIBUTISTA</option>
                                <option value="CONSUMIDOR FINAL" {{ old('Detalle') == 'CONSUMIDOR FINAL' ? 'selected' : '' }}>CONSUMIDOR FINAL</option>
                                <option value="I.V.A RESPONSABLE INSCRIPTO" {{ old('Detalle') == 'I.V.A RESPONSABLE INSCRIPTO' ? 'selected' : '' }}>I.V.A RESPONSABLE INSCRIPTO</option>
                                <option value="I.V.A SUJETO EXENTO" {{ old('Detalle') == 'I.V.A SUJETO EXENTO' ? 'selected' : '' }}>I.V.A SUJETO EXENTO</option>
                                <option value="SUJETO NO CATEGORIZADO" {{ old('Detalle') == 'SUJETO NO CATEGORIZADO' ? 'selected' : '' }}>SUJETO NO CATEGORIZADO</option>
                            </select>
                        </div>

                        <!-- Visible (Checkbox) -->
                        <div class="col-span-full">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="visible" name="visible" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ old('visible') ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="visible" class="font-medium text-gray-700">Visible</label>
                                    <p class="text-gray-500">Determina si este cliente está activo y visible.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white font-bold rounded-md shadow-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Guardar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
