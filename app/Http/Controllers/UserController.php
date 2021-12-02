<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use TCG\Voyager\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador', 'intendente', 'prensa', 'medico', 'plen_gestor', 'plen_entrenador']);

        $id = $request->user()->id;

        $user = User::find($id);

        $rol = Role::find($user->role_id);

        return view('user', ['user' => $user, 'role' => $rol->name]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador', 'intendente', 'prensa', 'medico']);

        $id = $request->user()->id;

        $user = User::find($id);

        $data = json_decode($request->get('user'));

        $user->name = $data->name;
        $user->email = $data->email;
        $user->password = ($data->password ? bcrypt($data->password) : $user->password);

        if($request->file('photo')) {
          $path = $request->file('photo')->store('public/users/' . date('FY'));
          $user->avatar = str_replace('/storage/', '', Storage::url($path));
        }

        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
