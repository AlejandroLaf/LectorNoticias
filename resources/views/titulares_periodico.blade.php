<!-- resources/views/mostrar_titulares.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Titulares del Periódico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div id="titulares-message">Cargando titulares...</div>
                    <ul id="titulares-list" style="display: none;"></ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @auth
            console.log('Usuario autenticado');
            console.log('User ID:', '{{ Auth::id() }}');

            // Extraer la ID del periódico de la URL
            const pathArray = window.location.pathname.split('/');
            const periodicoId = pathArray[pathArray.length - 1];

            fetch(`http://localhost:8000/api/ver-titulares/${periodicoId}`, {
                    method: 'GET',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    const titularesList = document.getElementById('titulares-list');
                    const titularesMessage = document.getElementById('titulares-message');

                    if (data && data.titulares && data.titulares.length > 0) {
                        titularesMessage.style.display = 'none';
                        titularesList.style.display = 'block';

                        // Iterar sobre los titulares y agregar elementos a la lista
                        data.titulares.forEach(titular => {
                            const li = document.createElement('li');
                            li.textContent = titular;
                            titularesList.appendChild(li);
                        });
                    } else {
                        titularesMessage.textContent = 'No hay titulares disponibles.';
                    }
                })
                .catch(error => {
                    console.error('Error en la solicitud a la API:', error);
                    const titularesMessage = document.getElementById('titulares-message');
                    titularesMessage.textContent = 'Error al obtener titulares desde la API';
                });
        @else
            window.location.href = '/';
        @endauth
        });
    </script>
</x-app-layout>
