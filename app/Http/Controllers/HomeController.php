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
          default:
            return view('home', ['role' => $rol->name]);
            break;
        }
    }
}
