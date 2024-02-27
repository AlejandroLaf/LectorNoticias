<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Periodicos') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center ">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                Añadir periodico
            </button>
        </div>
    </div>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($periodicos->isEmpty())
                    <p>No tienes periódicos asociados.</p>
                @else
                    <ul>
                        @foreach($periodicos as $periodico)
                        <li>
                            <div class="py-6 ">
                                <div class="flex justify-center font-bold mb-6">
                                    {{ $periodico->name }}
                                </div>

                                <div class=" py-2" ">
                                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-around ">
                                    <button class="bg-purple-500 hover:bg-purpleyellow-700 text-white font-bold py-2 px-4 rounded-full">
                                        Titulares
                                    </button>
                                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full">
                                        Ver datos
                                    </button>
                                    <button class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-full">
                                        Editar
                                    </button>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">
                                        Borrar
                                    </button>
                                    </div>
                                </div>
                        </li>
                        @endforeach
                    </ul>
                @endif
                </div>
            </div>
        </div>
    </div>




</x-app-layout>
