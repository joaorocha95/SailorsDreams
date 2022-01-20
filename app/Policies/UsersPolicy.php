<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsersPolicy
{
    use HandlesAuthorization;

    public function adminCheck()
    {
        if (auth()->check()) {
            $acctype = auth()->user()->acctype;
            return $acctype == 'Admin';
        }
        return false;
    }

    public function logCheck($id)
    {
        if (auth()->check())
            return $id == auth()->user()->id;
        return false;
    }

    public function outerCheck()
    {
        return auth()->check();
    }
}
