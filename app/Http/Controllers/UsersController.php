<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller {

    /**
     * Returns a specific User based on its E-mail
     * 
     * @return User
     */
    public function getUser(){
        $users = self::all();
        error_log("Uma possivel lista de users: ".$users);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check()) return redirect('/login');
        $this->authorize('list', Users::class);
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
        $user = new Users();     
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->birthdate = $request->input('birthdate');
        
        $password = $request->input('password');
        /*error_log($password);
        $password = Hash::make($password);
        error_log($password);*/
        $user->password = $password;
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
        $user = Users::find($id);
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
        $user = Users::find($id);
        
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
        $user = Users::find($id);

        $this->authorize('delete', $user);
        $user->delete();

        return $user;
    }

}
