<?php

namespace App\Http\Controllers;

use App\Models\Periodico;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Goutte\Client;

class PeriodicoController extends Controller
{
    public function verNombresPeriodicos(Request $request)
    {
        // Verificar si el usuario está autenticado mediante sesiones
        if (Auth::check()) {
            $userId = Auth::id();
            Log::info('ID del usuario: ' . $userId);

            // Obtener los periódicos del usuario autenticado
            $periodicos = Auth::user()->periodicos;

            // Verificar si hay periódicos
            if ($periodicos->isEmpty()) {
                return response()->json(['mensaje' => 'No hay periódicos disponibles para este usuario'], 404);
            }

            // Obtener nombres e IDs de los periódicos
            $nombresPeriodicos = $periodicos->map(function ($periodico) {
                return [
                    'id' => $periodico->id,
                    'name' => $periodico->name,
                ];
            });

            Log::info('Periodicos: ' . json_encode($nombresPeriodicos));

            // Devolver los nombres e IDs en formato JSON
            return response()->json(['periodicos' => $nombresPeriodicos]);
        }

        // Si el usuario no está autenticado, devolver una respuesta no autorizada
        return response()->json(['mensaje' => 'Usuario no autenticado'], 401);
    }

    public function agregarPeriodico(Request $request)
    {
        try {
            Log::info('Inicio de la solicitud para agregar un periódico.');

            // Verificar si el usuario está autenticado mediante sesiones
            if (Auth::check()) {
                // Validar los datos de la solicitud
                $request->validate([
                    'url' => 'required|url',
                    'name' => 'required|string',
                ]);

                Log::info('Datos de la solicitud validados correctamente.');

                // Obtener el usuario autenticado
                $user = Auth::user();

                Log::info('Usuario autenticado obtenido correctamente.');

                // Crear un nuevo periódico y asociarlo al usuario
                $periodico = $user->periodicos()->create([
                    'url' => $request->input('url'),
                    'name' => $request->input('name'),
                ]);

                Log::info('Periódico creado y asociado al usuario correctamente.');

                // Devolver la respuesta en formato JSON
                return response()->json(['mensaje' => 'Periódico añadido correctamente', 'periodico' => $periodico]);
            } else {
                // Si el usuario no está autenticado, devolver una respuesta no autorizada
                Log::info('Usuario no autenticado.');

                return response()->json(['mensaje' => 'Usuario no autenticado'], 401);
            }
        } catch (\Exception $e) {
            // Manejar errores
            Log::error('Error al agregar el periódico: ' . $e->getMessage());
            Log::error($e->getTraceAsString()); // Agregar información de seguimiento

            return response()->json(['error' => 'Error al agregar el periódico'], 500);
        }
    }

    public function mostrarTitulares(Request $request)
    {
        $user = $request->user();

        // Obtener los periódicos del usuario
        $periodicos = $user->periodicos;

        // Recopilar los titulares de todos los periódicos
        $titulares = [];

        foreach ($periodicos as $periodico) {
            $url = $periodico->url;

            // Utilizar Goutte para hacer scraping de los titulares
            $client = new Client();
            $crawler = $client->request('GET', $url);

            // Ejemplo: Obtener titulares dentro de elementos con la clase 'titular'
            $titularesEnPagina = $crawler->filter('h2')->each(function ($node) {
                return $node->text();
            });

            // Almacenar los titulares en el array
            $titulares[] = [
                'periodico' => $periodico->name,
                'titulares' => $titularesEnPagina,
            ];
        }

        // Devolver los titulares en formato JSON
        return response()->json(['titulares' => $titulares]);
    }

    public function mostrarTitularesPorPeriodico(Request $request, $id)
    {
        $user = $request->user();

        // Obtener el periódico específico por ID
        $periodico = $user->periodicos()->findOrFail($id);

        // Utilizar Goutte para hacer scraping de los titulares
        $client = new Client();
        $crawler = $client->request('GET', $periodico->url);

        // Ejemplo: Obtener titulares dentro de elementos <h2>
        $titularesEnPagina = $crawler->filter('h2')->each(function ($node) {
            return $node->text();
        });

        // Devolver los titulares en formato JSON
        return response()->json([
            'periodico' => $periodico->name,
            'titulares' => $titularesEnPagina,
        ]);
    }

    public function mostrarDatosPorPeriodico(Request $request, $id)
    {
        try {
            // Verificar si el usuario está autenticado mediante sesiones
            if (Auth::check()) {
                // Obtener el usuario autenticado
                $user = $request->user();

                Log::info('Datos: ' . json_encode($id));

                // Obtener el periódico específico por ID y asociado al usuario
                $periodico = $user->periodicos()->findOrFail($id);

                // Obtener todos los datos del periódico desde la base de datos
                $datosDelPeriodico = [
                    'id' => $periodico->id,
                    'name' => $periodico->name,
                    'url' => $periodico->url,
                    // Agrega aquí cualquier otro dato que desees incluir
                ];

                Log::info('Datos: ' . json_encode($datosDelPeriodico));

                // Devolver los datos en formato JSON
                return response()->json([
                    'periodico' => $periodico->name,
                    'datos' => $datosDelPeriodico,
                ]);
            } else {
                // Si el usuario no está autenticado, devolver una respuesta no autorizada
                return response()->json(['mensaje' => 'Usuario no autenticado'], 401);
            }
        } catch (\Exception $e) {
            // Manejar errores
            Log::error('Error al obtener datos del periódico: ' . $e->getMessage());
            Log::error($e->getTraceAsString()); // Agregar información de seguimiento

            return response()->json(['error' => 'Error al obtener datos del periódico'], 500);
        }
    }

    public function borrarPeriodico(Request $request, $id)
{
    try {
        // Verificar si el usuario está autenticado mediante sesiones
        if (Auth::check()) {
            $user = $request->user();

            // Obtener el periódico específico por ID y asociado al usuario
            $periodico = $user->periodicos()->findOrFail($id);

            // Borrar el periódico
            $periodico->delete();

            // Devolver la respuesta en formato JSON
            return response()->json(['mensaje' => 'Periódico borrado correctamente']);
        } else {
            // Si el usuario no está autenticado, devolver una respuesta no autorizada
            return response()->json(['mensaje' => 'Usuario no autenticado'], 401);
        }
    } catch (\Exception $e) {
        // Manejar errores
        Log::error('Error al borrar el periódico: ' . $e->getMessage());
        Log::error($e->getTraceAsString()); // Agregar información de seguimiento

        return response()->json(['error' => 'Error al borrar el periódico'], 500);
    }
}
    public function editarPeriodico(Request $request, $id)
{
    try {
        // Verificar si el usuario está autenticado mediante sesiones
        if (Auth::check()) {
            $user = $request->user();

            // Obtener el periódico específico por ID y asociado al usuario
            $periodico = $user->periodicos()->findOrFail($id);

            // Validar los datos de la solicitud
            $request->validate([
                'url' => 'required|url',
                'name' => 'required|string',
            ]);

            // Actualizar los datos del periódico
            $periodico->update([
                'url' => $request->input('url'),
                'name' => $request->input('name'),
            ]);

            // Devolver la respuesta en formato JSON
            return response()->json(['mensaje' => 'Periódico modificado correctamente']);
        } else {
            // Si el usuario no está autenticado, devolver una respuesta no autorizada
            return response()->json(['mensaje' => 'Usuario no autenticado'], 401);
        }
    } catch (\Exception $e) {
        // Manejar errores
        Log::error('Error al editar el periódico: ' . $e->getMessage());
        Log::error($e->getTraceAsString()); // Agregar información de seguimiento

        return response()->json(['error' => 'Error al editar el periódico'], 500);
    }
}
}
