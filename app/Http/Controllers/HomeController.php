<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Auth;
use Illuminate\Http\Request;
use TCG\Voyager\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rol = Role::find(Auth::user()->role_id);

        switch( $rol->name ) {
          case 'admin':
            return redirect('admin');
            break;
          // case 'gerente':
          //   return view('home_gerente', ['role' => $rol->name]);
          //   break;
          // case 'rrhh':
          //   return view('home_rrhh', ['role' => $rol->name]);
          //   break;
          // case 'entrenador':
          //   return view('home_entrenador', ['role' => $rol->name]);
          //   break;
          // case 'intendente':
          //   return view('home_intendente', ['role' => $rol->name]);
          //   break;
          // case 'prensa':
          //   return view('home_prensa', ['role' => $rol->name]);
          //   break;
          // case 'medico':
          //   return view('home_medico', ['role' => $rol->name]);
          //   break;
          default:
            return view('home_default', ['role' => $rol->name]);
            break;
        }
    }
}
