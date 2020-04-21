<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Explotacion;

class ExplotacionController extends Controller
{
  public function index(Request $request)
  {
      $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador']);

      $data = \App\Explotacion::orderBy('desc', 'asc')->get();

      return response()->json($data, 200);
  }
}
