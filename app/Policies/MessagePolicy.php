<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function index()
    {
    }

    public function messagePage()
    {
    }

    public function sendMessage()
    {
    }

    public function show()
    {
    }
}
