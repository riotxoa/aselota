<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EntrEvolucion;

class EntrEvolucionController extends Controller
{
  public function index(Request $request)
  {
      $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador']);

      $data = \App\EntrEvolucion::orderBy('name', 'asc')->get();

      return response()->json($data, 200);
  }
}
