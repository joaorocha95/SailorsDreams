<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apllication;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Policies\ApplicationPolicy;



class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $pol = new ApplicationPolicy();

        if ($pol->adminCheck()) {
            $applications = DB::table('application')->where('id', 'iLIKE', '%' . $request->term . '%')
                ->get();

            return view('pages.applications', ['applications' => $applications]);
        }

        abort(404);
    }
    public function create()
    {
        $pol = new ApplicationPolicy();

        if ($pol->usrCliCheck()) {
            return view('applications.newApplication');
        }

        abort(404);
    }

    public function submitApplication(Request $request)
    {
        $pol = new ApplicationPolicy();

        if ($pol->usrCliCheck()) {

            $application = new Application();

            if ($request->id == null)
                abort(404);

            $application->title = $request->input('title');
            $application->description = $request->input('description');
            $application->userid = $request->id;

            $application->save();

            $user = User::find($request->id);
            if ($user == null)
                abort(404);

            return redirect()->route('user.id', $user);
        }

        abort(404);
    }

    public function show($id)
    {
        $pol = new ApplicationPolicy();

        if ($pol->usrCliAdmCheck()) {
            $application = Application::find($id);
            if ($application == null)
                abort(404);
            return view('applications.application', ["application" => $application]);
        }

        abort(404);
    }

    public function acceptApplication($id)
    {
        $pol = new ApplicationPolicy();

        if ($pol->evaluateCheck($id)) {
            $application = Application::find($id);
            if ($application == null)
                abort(404);

            $user = User::find($application->userid);
            if ($user == null)
                abort(404);



            $application->application_state = 'Accepted';
            $user->acctype = 'Seller';

            $application->save();
            $user->save();

            return back()->with(["application" => $application]);
        }

        abort(404);
    }

    public function rejectApplication($id)
    {
        $pol = new ApplicationPolicy();

        if ($pol->evaluateCheck($id)) {
            $application = Application::find($id);
            if ($application == null)
                abort(404);

            $application->application_state = 'Rejected';

            $application->save();
            return back()->with(["application" => $application]);
        }

        abort(404);
    }
}
