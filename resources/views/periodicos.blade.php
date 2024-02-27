<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Periodicos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center">
            <a href="{{ url('/periodicos/agregar') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                Añadir periodico
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
            console.log('Token:', '{{ Auth::user()->api_token }}');

            fetch('http://localhost/api/periodicos', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer {{ Auth::user()->api_token }}',
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
                            li.textContent = periodico.name;
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
    </script>
</x-app-layout>
