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
        Log::info('ID del usuario: ' . $userId); // Agrega esta línea para el log

        // Obtener los periódicos del usuario autenticado
        $periodicos = Auth::user()->periodicos;


        // Verificar si hay periódicos
        if ($periodicos->isEmpty()) {
            return response()->json(['mensaje' => 'No hay periódicos disponibles para este usuario'], 404);
        }

        // Obtener solo los nombres de los periódicos
        $nombresPeriodicos = $periodicos->pluck('name');

        Log::info('Periodicos: ' . $nombresPeriodicos); // Agrega esta línea para el log

        // Devolver los nombres en formato JSON
        return response()->json(['nombres_periodicos' => $nombresPeriodicos]);
    }

    // Si el usuario no está autenticado, devolver una respuesta no autorizada
    return response()->json(['mensaje' => 'Usuario no autenticado'], 401);
}


    public function agregarPeriodico(Request $request)
    {
        try {
            Log::info('Inicio de la solicitud para agregar un periódico.');

            // Validar los datos de la solicitud
            $request->validate([
                'url' => 'required|url',
                'name' => 'required|string',
            ]);

            Log::info('Datos de la solicitud validados correctamente.');

            // Obtener el usuario autenticado
            $user = $request->user();

            Log::info('Usuario autenticado obtenido correctamente.');

            // Crear un nuevo periódico y asociarlo al usuario
            $periodico = $user->periodicos()->create([
                'url' => $request->input('url'),
                'name' => $request->input('name'),
            ]);

            Log::info('Periódico creado y asociado al usuario correctamente.');

            // Devolver la respuesta en formato JSON
            return response()->json(['mensaje' => 'Periódico añadido correctamente', 'periodico' => $periodico]);
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
        $user = $request->user();

        // Obtener el periódico específico por ID
        $periodico = $user->periodicos()->findOrFail($id);

        // Obtener todos los datos del periódico desde la base de datos
        $datosDelPeriodico = $periodico->toArray();

        // Devolver los datos en formato JSON
        return response()->json([
            'periodico' => $periodico->name,
            'datos' => $datosDelPeriodico,
        ]);
    }

    public function borrarPeriodico(Request $request, $id)
    {
        $user = $request->user();

        // Obtener el periódico específico por ID
        $periodico = $user->periodicos()->findOrFail($id);

        // Borrar el periódico
        $periodico->delete();

        // Devolver la respuesta en formato JSON
        return response()->json(['mensaje' => 'Periódico borrado correctamente']);
    }

    public function modificarPeriodico(Request $request, $id)
    {
        $user = $request->user();

        // Obtener el periódico específico por ID
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
    }
}
