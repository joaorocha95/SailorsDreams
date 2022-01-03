<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check()) return redirect('/login');
        $this->authorize('list', User::class);
        $users = Auth::user()->users()->orderBy('id')->get();
        return view('pages.users', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        error_log("AQUI");
        //error_log($request->input('username'));
        $user = new Users();
        $user->username = $request->input('username');
        
        //$this->authorize('create', $user);
        $user->id = 13;
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->birthdate = $request->input('birthdate');
        $user->password = $request->input('password');
        $user->phone = $request->input('phone');
        error_log($user);
        
        $user->save();

        return $user;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $this->authorize('show', $user);
        return view('pages.user', ['user' => $user]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $birthDate, $banned, $accType, $img)
    {
        $user = User::find($id);
        
        $this->authorize('update', $user);
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->birthDate = $birthDate;
        $user->password = $request->input('password');
        $user->banned = $banned;
        $user->accType = $accType;
        $user->img = $img;
        $user->phone = $request->input('phone');
        $user->save();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $user = User::find($id);

        $this->authorize('delete', $user);
        $user->delete();

        return $user;
    }
}
