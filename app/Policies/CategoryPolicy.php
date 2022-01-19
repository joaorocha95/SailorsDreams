<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;


    public function index()
    {
    }

    public function adminIndex()
    {
    }

    public function showNewForm()
    {
    }

    public function showUpdateForm()
    {
    }

    public function create()
    {
    }


    public function show()
    {
    }


    public function adminShow()
    {
    }

    public function delete()
    {
    }

    public function update()
    {
    }
}
