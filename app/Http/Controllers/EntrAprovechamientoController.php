<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EntrAprovechamiento;

class EntrAprovechamientoController extends Controller
{
  public function index(Request $request)
  {
      $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador']);

      $data = \App\EntrAprovechamiento::orderBy('name', 'asc')->get();

      return response()->json($data, 200);
  }
}
