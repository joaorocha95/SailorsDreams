<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Policies\UsersPolicy;


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
        $pol = new UsersPolicy();

        if ($pol->adminCheck()) {
            $users = DB::table('users')
                ->get();

            return view('admin.users', ['users' => $users]);
        }

        abort(404);
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
        $pol = new UsersPolicy();
        $user = User::find($id);

        if ($pol->logCheck($id))
            return view('pages.userprofile', ["user" => $user]);

        if ($pol->outerCheck($id))
            return view('pages.outerprofile', ["user" => $user]);
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function outerShow($id)
    {
        $pol = new UsersPolicy();
        $user = User::find($id);

        if ($pol->outerCheck())
            return view('pages.outerprofile', ["user" => $user]);

        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function adminShow($id)
    {
        $pol = new UsersPolicy();

        if ($pol->adminCheck()) {
            $user = User::find($id);
            if ($user == null)
                abort(404);

            return view('admin.userDetails', ["user" => $user]);
        }

        abort(404);
    }

    public function update($id)
    {
        $pol = new UsersPolicy();

        if ($pol->logCheck(auth()->user()->id)) {
            $user = User::find($id);
            return view('pages.editUserProfile', ["user" => $user]);
        }

        abort(404);
    }


    public function updateProfile(Request $request, $id)
    {
        $pol = new UsersPolicy();

        $id_aux = auth()->user()->id;
        $user = User::find($id_aux);

        if ($pol->logCheck($id_aux)) {

            if ($user == null)
                abort(404);

            if ($request->input('phone') != null)
                $user->phone = $request->input('phone');

            if ($request->file('pic') != null) {
                $file = $request->file('pic');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('uploads/avatarImages', $filename);
                $user->img = $filename;
            }

            if ($request->input('password') != null)
                $user->password = bcrypt($request->input('password'));

            $user->save();


            if ($user->acctype == 'Admin')
                return redirect()->route('user.id', ["id" => $user->id]);
            else
                return redirect()->route('user.id', ["id" => $user->id]);
        }

        abort(404);
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
        $pol = new UsersPolicy();

        if ($pol->adminCheck()) {
            $user = User::find($id);

            $user->banned = true;
            $user->save();

            return redirect()->route('accounts.id', ["id" => $id]);
        }

        abort(404);
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
        $pol = new UsersPolicy();

        if ($pol->adminCheck(auth()->user()->id)) {
            $user = User::find($id);

            $user->banned = false;
            $user->save();

            return redirect()->route('accounts.id', ["id" => $id]);
        }

        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $pol = new UsersPolicy();

        if ($pol->logCheck(auth()->user()->id)) {
            $user = User::find($id);
            $user->delete();

            return redirect()->route('home');
        }
        abort(404);
    }
}
