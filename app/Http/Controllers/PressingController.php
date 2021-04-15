<?php

namespace App\Http\Controllers;

use App\Services\Pressing\Pressing;
use Illuminate\Http\JsonResponse;

class PressingController extends Controller
{
    private ?int $error = null;

    private function error(int $error = null, string $message = null, int $status = 400): JsonResponse
    {
        if ($error) {
            $this->error = $error;
        }

        switch ($this->error) {
            case 1:
                $message = 'Impossible d\'obtenir vos informations';
                break;
            case 2:
                $message = 'Impossible d\'obtenir les commandes';
                break;
            default:
                $message ?: $message = 'Undefined error';
        }

        return new JsonResponse([
            'error' => $this->error,
            'message' => $message
        ], $status);
    }

    public function index()
    {
        // TODO: check que le mec est bien log sur l'api et on envoie l'id du provider dans la vue (puis on modifie l'id dans la vue pour le call axios)
        if (!$order = Pressing::getProviderOrder(1)) {
            return $this->error(2);
        }

        return view('Pressing/home', [
            'orders' => $order
        ]);
    }
}
