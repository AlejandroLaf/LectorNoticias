<!-- resources/views/agregar_periodico.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agregar Periódico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="agregarPeriodicoForm" action="" method="post">
                        @csrf
                        <div class="mb-4">
                            <label for="url" class="block text-gray-700 text-sm font-bold mb-2">URL:</label>
                            <input type="url" name="url" id="url" class="border-2 border-gray-300 p-2 w-full" required>
                        </div>
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                            <input type="text" name="name" id="name" class="border-2 border-gray-300 p-2 w-full" required>
                        </div>
                        <div>
                            <button type="button" onclick="agregarPeriodico()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                                Agregar Periódico
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function agregarPeriodico() {
            // Obtener los datos del formulario
            var formData = new FormData(document.getElementById('agregarPeriodicoForm'));

            // Realizar la petición AJAX
            fetch('http://localhost:8000/api/periodicos/agregar', {
                method: 'POST',
                body: formData,
                credentials: 'include', // Asegura que las cookies se incluyan en la solicitud
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Agregar el token CSRF
                },
            })
            .then(response => response.json())
            .then(data => {
                // Manejar la respuesta aquí (puede ser mostrar un mensaje de éxito, etc.)
                console.log(data);
            })
            .catch(error => {
                // Manejar errores aquí
                console.error('Error en la solicitud:', error);
            });
        }
    </script>
</x-app-layout>
