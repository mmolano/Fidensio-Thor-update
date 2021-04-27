<?php

namespace App\Http\Controllers;

use App\Services\Pressing\Pressing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private ?int $error = null;

    private function error(?int $error, ?string $message = null): JsonResponse
    {
        if ($error) {
            $this->error = $error;
        }

        switch ($this->error) {
            case 1:
                $message = 'Votre email ou votre mot de passe n\'est pas valide';
                break;
            default:
                $message ?: $message = 'Undefined error';
        }

        return response()->json([
            'status'    => 'error',
            'message' => $message,
        ], 400);
    }

    public function index()
    {
        if (session('authenticated')) {
            return redirect('/');
        }

        return view('authenticate');
    }

    public function login(Request $request)
    {
        if (!$user = Pressing::checkProvider($request->email, $request->password)) {
            return $this->error(1);
        }

        session()->put([
            'authenticated' => time(),
            'authId' => $user['id'],
            'authName' => $user['name']
        ]);

        session()->save();
    }

    public function logout()
    {
        session()->forget([
            'authenticated',
            'authName',
            'authId'
        ]);

        flash('Vous avez été déconnecté !');
        return redirect('/login');
    }
}
