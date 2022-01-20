<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function adminCheck()
    {
        if (auth()->check()) {
            $acctype = auth()->user()->acctype;
            return $acctype == 'Admin';
        }
        return false;
    }

    public function usrCliCheck()
    {
        if (auth()->check()) {
            $acctype = auth()->user()->acctype;
            return $acctype == 'User' || $acctype == 'Client';
        }
        return false;
    }

    public function usrCliAdmCheck()
    {
        if (auth()->check()) {
            $acctype = auth()->user()->acctype;
            return $acctype == 'User' || $acctype == 'Client' || $acctype == 'Admin';
        }
        return false;
    }
}
