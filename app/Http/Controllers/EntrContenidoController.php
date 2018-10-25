<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EntrContenido;

class EntrContenidoController extends Controller
{
  public function index(Request $request)
  {
      $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador']);

      $data = \App\EntrContenido::orderBy('name', 'asc')->get();

      return response()->json($data, 200);
  }
}
