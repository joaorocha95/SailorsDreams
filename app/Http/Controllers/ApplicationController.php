<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apllication;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = DB::table('application')->where('id', 'iLIKE', '%' . $request->term . '%')
            ->get();

        return view('pages.applications', ['applications' => $applications]);
    }
    public function create()
    {
        return view('applications.newApplication');
    }
    public function submitApplication(Request $request)
    {
        $application = new Application();

        error_log("----------------------------------------------" . $request->input('title'));

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

    public function show($id)
    {
        $application = Application::find($id);
        if ($application == null)
            abort(404);
        return view('applications.application', ["application" => $application]);
    }
    public function acceptApplication($id)
    {
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
    public function rejectApplication($id)
    {
        $application = Application::find($id);
        if ($application == null)
            abort(404);

        $application->application_state = 'Rejected';

        $application->save();
        return back()->with(["application" => $application]);
    }
}
