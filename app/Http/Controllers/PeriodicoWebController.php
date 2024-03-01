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

    public function mostrarDatosPorPeriodico(Request $request, $id)
    {
        return view('datos_periodico');
    }

    public function editarPeriodico(Request $request, $id)
    {
        return view('editar_periodico');
    }
}
