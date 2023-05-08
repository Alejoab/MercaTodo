<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    /**
     * Create the home page for the administrator.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Administrator/Home');
    }
}
