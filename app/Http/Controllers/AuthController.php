<?php


namespace App\Http\Controllers;


class AuthController extends Controller
{
    public function index()
    {
        // check si l'user est co si non redirect

        return view('authenticate');
    }

    public function login()
    {
        // check si l'user est co si non redirect

        return 'success';
    }
}
