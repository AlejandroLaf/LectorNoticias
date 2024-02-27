<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PeriodicoWebController extends Controller
{
    public function verNombresPeriodicos()
    {
                return view('periodicos');

    }

    public function mostrarFormularioAgregar()
    {
        return view('agregar_periodico');
    }

    public function agregarPeriodico(Request $request)
    {
        try {
            Log::info('Antes de la solicitud HTTP');

            $response = Http::withToken($request->user()->api_token)->post(config('app.api_url') . '/api/periodicos/agregar', [
                'url' => $request->input('url'),
                'name' => $request->input('name'),
            ]);

            Log::info('Después de la solicitud HTTP');

            if ($response->successful()) {
                return redirect()->route('dashboard')->with('success', 'Periódico añadido correctamente');
            }

            throw new \Exception($response->json('error', 'Error al agregar el periódico'));
        } catch (\Exception $e) {
            // Manejar todas las excepciones
            Log::error('Error al agregar el periódico: ' . $e->getMessage());

            if ($e instanceof \Illuminate\Http\Client\RequestException && $e->response) {
                Log::info('Respuesta de la API:', ['body' => $e->response->body()]);
            }

            return view('error', ['mensaje' => 'Error al agregar el periódico']);
        }
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
}
