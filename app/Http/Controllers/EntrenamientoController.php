<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entrenamiento;

class EntrenamientoController extends Controller
{
  public function index(Request $request)
  {
      $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador']);

      $entrenamientos = \App\Entrenamiento::orderBy('fecha', 'desc')->get();

      return response()->json($entrenamientos, 200);
  }
}
