<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Periodicos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center">
            <a href="{{ url('/periodicos/nuevo') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                Añadir periódico
            </a>
        </div>
    </div>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p id="periodicos-message">Cargando periódicos...</p>
                    <ul id="periodicos-list" style="display: none;"></ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @auth
            console.log('Usuario autenticado');
            console.log('User ID:', '{{ Auth::id() }}');

            fetch('http://localhost:8000/api/periodicos', {
                    method: 'GET',
                    credentials: 'include', // Asegura que las cookies se incluyan en la solicitud
                    headers: {
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    const periodicosList = document.getElementById('periodicos-list');
                    const periodicosMessage = document.getElementById('periodicos-message');

                    if (data && data.periodicos && data.periodicos.length > 0) {
                        periodicosMessage.style.display = 'none';
                        periodicosList.style.display = 'block';

                        // Iterar sobre los periódicos y agregar elementos a la lista
                        data.periodicos.forEach(periodico => {
                            const li = document.createElement('li');

                            // Estructura similar a lo que proporcionaste
                            li.innerHTML = `
                            <div class="py-6">
                                <div class="flex justify-center font-bold mb-6">
                                    ${periodico.name}
                                </div>
                                <div class="py-2">
                                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-around">
                                        <button class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-full">
                                            Titulares
                                        </button>
                                        <a href="/periodicos/${periodico.id}">
                                            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full">
                                                Ver datos
                                            </button>
                                        </a>
                                        <a href="/periodicos/editar/${periodico.id}">
                                        <button class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-full">
                                            Editar
                                        </button>
                                        </a>
                                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full" onclick="borrarPeriodico(${periodico.id})">
                                            Borrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;

                            periodicosList.appendChild(li);
                        });
                    } else {
                        periodicosMessage.textContent = 'No tienes periódicos asociados.';
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud a la API:', error);
                    const periodicosMessage = document.getElementById('periodicos-message');
                    periodicosMessage.textContent = 'Error al obtener periódicos desde la API';
                });
        @else
            window.location.href = '/';
        @endauth
        });

        function borrarPeriodico(id) {
            // Confirmar con el usuario antes de borrar
            if (confirm('¿Estás seguro de que deseas borrar este periódico?')) {
                fetch(`http://localhost:8000/api/periodicos/borrar/${id}`, {
                        method: 'DELETE',
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
                        // Recargar la página o realizar otras acciones necesarias
                        location.reload();
                    })
                    .catch(error => {
                        // Manejar errores aquí
                        console.error('Error en la solicitud:', error);
                        // Mostrar un mensaje de error al usuario u otras acciones necesarias
                    });
            }
        }
    </script>
</x-app-layout>D
