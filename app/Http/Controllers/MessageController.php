<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check()) return redirect('/login');
        $this->authorize('list', Message::class);
        $messages = Auth::user()->messages()->orderBy('id')->get();
        return view('pages.messages', ['messages' => $messages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id, $associated_order, $associated_ticket)
    {
        $message = new Message();

        $this->authorize('create', $message);
        $message->id = $id;
        $message->associated_order = $associated_order;
        $message->associated_ticket = $associated_ticket;
        $message->message = $request->input('message');
        $message->save();

        return $message;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $message = Message::find($id);
        $this->authorize('show', $message);
        return view('pages.message', ['message' => $message]);
    }
}
