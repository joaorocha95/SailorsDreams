<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{

    /**
     * Returns a specific User based on its E-mail
     * 
     * @return User
     */
    public function getUser()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = DB::table('users')
            ->get();

        return view('admin.users', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = new User();
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->birthdate = $request->input('birthdate');

        $password = $request->input('password');
        $user->password = $password;
        $user->phone = $request->input('phone');

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
        if ($user == null)
            abort(404);

        return view('pages.userprofile', ["user" => $user]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function adminShow($id)
    {
        $user = User::find($id);
        if ($user == null)
            abort(404);

        return view('admin.userDetails', ["user" => $user]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function ban($id)
    {
        $user = User::find($id);

        $user->banned = true;
        $user->save();

        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function unban($id)
    {
        $user = User::find($id);

        $user->banned = false;
        $user->save();

        return view('admin.userDetails', ["user" => $user]);
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
