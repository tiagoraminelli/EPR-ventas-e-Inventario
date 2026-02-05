<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cliente</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <div class="w-64 bg-white border-r">
        <x-admin-nav />
    </div>

    <!-- Contenido -->
    <main class="flex-1 p-6">

        <div class="max-w-4xl mx-auto bg-white border">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h1 class="text-xl font-semibold text-gray-800">
                    Nuevo Cliente
                </h1>

                <a href="{{ route('clientes.index') }}"
                   class="text-sm text-gray-600 hover:text-black">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>

            <!-- Errores -->
            @if ($errors->any())
                <div class="mx-6 mt-4 p-3 border border-red-200 bg-red-50 text-sm text-red-700">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('clientes.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- Razón Social -->
                    <div>
                        <label class="text-xs text-gray-500">Razón Social</label>
                        <input type="text" name="RazonSocial"
                               value="{{ old('RazonSocial') }}"
                               class="w-full border px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label class="text-xs text-gray-500">Nombre Completo</label>
                        <input type="text" name="NombreCompleto"
                               value="{{ old('NombreCompleto') }}"
                               class="w-full border px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
                    </div>

                    <!-- CUIT/DNI -->
                    <div>
                        <label class="text-xs text-gray-500">CUIT / DNI</label>
                        <input type="text" name="cuit_dni"
                               value="{{ old('cuit_dni') }}"
                               class="w-full border px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
                    </div>

                    <!-- Domicilio -->
                    <div>
                        <label class="text-xs text-gray-500">Domicilio</label>
                        <input type="text" name="Domicilio"
                               value="{{ old('Domicilio') }}"
                               class="w-full border px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
                    </div>

                    <!-- Localidad -->
                    <div>
                        <label class="text-xs text-gray-500">Localidad</label>
                        <input type="text" name="Localidad"
                               value="{{ old('Localidad') }}"
                               class="w-full border px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="text-xs text-gray-500">Email</label>
                        <input type="email" name="Email"
                               value="{{ old('Email') }}"
                               class="w-full border px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="text-xs text-gray-500">Teléfono</label>
                        <input type="text" name="Telefono"
                               value="{{ old('Telefono') }}"
                               class="w-full border px-3 py-2 text-sm focus:outline-none focus:border-gray-900">
                    </div>

                    <!-- Tipo Cliente -->
                    <div>
                        <label class="text-xs text-gray-500">Tipo de Cliente</label>
                        <select name="TipoCliente"
                                class="w-full border px-3 py-2 text-sm bg-white focus:outline-none focus:border-gray-900">
                            <option value="Persona">Persona</option>
                            <option value="Empresa">Empresa</option>
                            <option value="Institucion Publica">Institución Pública</option>
                        </select>
                    </div>

                    <!-- Detalle -->
                    <div>
                        <label class="text-xs text-gray-500">Condición Fiscal</label>
                        <select name="Detalle"
                                class="w-full border px-3 py-2 text-sm bg-white focus:outline-none focus:border-gray-900">
                            <option>MONOTRIBUTISTA</option>
                            <option>CONSUMIDOR FINAL</option>
                            <option>I.V.A RESPONSABLE INSCRIPTO</option>
                            <option>I.V.A SUJETO EXENTO</option>
                            <option>SUJETO NO CATEGORIZADO</option>
                        </select>
                    </div>

                    <!-- Visible -->
                    <div class="col-span-full flex items-center gap-2 pt-2">
                        <input type="checkbox" name="visible" id="visible"
                               class="border-gray-400">
                        <label for="visible" class="text-sm text-gray-600">
                            Cliente activo
                        </label>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end pt-4 border-t">
                    <button type="submit"
                            class="px-6 py-2 bg-gray-900 text-white text-sm hover:bg-black transition">
                        Guardar Cliente
                    </button>
                </div>

            </form>

        </div>
    </main>
</div>

</body>
</html>
