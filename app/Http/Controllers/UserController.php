<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Shows the home page for the user.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('User/Home');
    }
}
