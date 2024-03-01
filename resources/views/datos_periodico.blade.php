<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Datos del periódico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    ID DEL PERIÓDICO: <span id="periodico-id"></span>
                </div>
                <div class="p-6 text-gray-900">
                    NOMBRE DEL PERIÓDICO: <span id="periodico-nombre"></span>
                </div>
                <div class="p-6 text-gray-900">
                    URL DEL PERIÓDICO: <span id="periodico-url"></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    @auth
    console.log('Usuario autenticado');
    console.log('User ID:', '{{ Auth::id() }}');

    const pathArray = window.location.pathname.split('/');
    const id = pathArray[pathArray.length - 1];

    fetch(`http://localhost:8000/api/periodicos/${id}`, {
        method: 'GET',
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        console.log('Datos recibidos de la API:', data);

        const periodicoId = document.getElementById('periodico-id');
        const periodicoNombre = document.getElementById('periodico-nombre');
        const periodicoUrl = document.getElementById('periodico-url');

        console.log('Elementos DOM encontrados:', periodicoId, periodicoNombre, periodicoUrl);

        if (data && data.periodico && data.datos) {
            console.log('Actualizando elementos DOM con datos:', data.periodico, data.datos);

            periodicoId.textContent = data.periodico;
            periodicoNombre.textContent = data.datos.name;
            periodicoUrl.textContent = data.datos.url;
        } else {
            console.error('Datos del periódico no encontrados en la respuesta API.');
        }
    })
    .catch(error => {
        console.error('Error en la solicitud a la API:', error);
    });
    @else
        window.location.href = '/';
    @endauth
});
    </script>
</x-app-layout>
