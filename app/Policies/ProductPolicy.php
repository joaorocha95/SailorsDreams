<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;


    public function index()
    {
        return false;
    }

    public function adminIndex()
    {
    }

    public function create()
    {
    }

    public function show()
    {
        return false;
    }

    public function adminShow()
    {
    }

    public function showNewForm()
    {
    }

    public function myProducts()
    {
    }

    public function showUpdateForm()
    {
    }

    public function updatepage()
    {
    }


    public function delete()
    {
    }
}
