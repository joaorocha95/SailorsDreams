<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function index()
    {
    }

    function dateDiff()
    {
    }

    protected function Purchase()
    {
    }

    protected function Loan()
    {
    }

    public function create()
    {
    }

    public function show()
    {
    }

    public function update()
    {
    }
}
