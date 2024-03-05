<!-- resources/views/mostrar_titulares.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mostrar Titulares') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p id="titulares-message">Cargando titulares...</p>
                    <div id="titulares-container" style="display: none;">
                        {{-- Contenido generado dinámicamente --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilos CSS */
        #titulares-container h1 {
            font-weight: bold;
            font-size: 20px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @auth
            console.log('Usuario autenticado');

            fetch('http://localhost:8000/api/ver-titulares', {
                    method: 'GET',
                    credentials: 'include', // Asegura que las cookies se incluyan en la solicitud
                    headers: {
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    const titularesContainer = document.getElementById('titulares-container');
                    const titularesMessage = document.getElementById('titulares-message');

                    if (data && data.titulares && data.titulares.length > 0) {
                        titularesMessage.style.display = 'none';
                        titularesContainer.style.display = 'block';

                        // Iterar sobre los titulares y agregar elementos a la lista
                        data.titulares.forEach(titular => {
                            const h1 = document.createElement('h1');
                            h1.textContent = titular.periodico;

                            const ul = document.createElement('ul');
                            titular.titulares.forEach(t => {
                                const li = document.createElement('li');
                                li.textContent = t;
                                ul.appendChild(li);
                            });

                            titularesContainer.appendChild(h1);
                            titularesContainer.appendChild(ul);
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
