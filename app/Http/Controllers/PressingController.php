<?php

namespace App\Http\Controllers;

use App\Services\Pressing\Pressing;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PressingController extends Controller
{
    private ?int $error = null;
    private array $status = [
        'default' => 1,
        'pickupDone' => 2,
        'processing' => 3,
        'finished' => 5
    ];

    private function error(?int $error, ?string $specialErrors = null, ?string $message = null): RedirectResponse
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
            case 3:
                $message = 'Une erreur est survenue, impossible d\'obtenir les informations sur la commande';
                Log::error('Impossible de récupérer certaines informations sur la commande',
                    [
                        'Error' => $this->error,
                        'validationErrors' => json_decode($specialErrors)
                    ]);
                break;
            case 4:
                $message = 'Impossible de changer le statut de la commande';
                break;
            default:
                $message ?: $message = 'Undefined error';
        }

        flash($message)->error();
        return back();
    }

    public function commitStatus(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'orderId' => ['required', 'integer'],
            'statusId' => ['required', 'integer']
        ]);

        if ($validation->fails()) {
            return $this->error(3, json_encode($validation->errors()));
        }

//        if (!$order = Pressing::changeStatus($request->orderId, $request->statusId)) {
//            return $this->error(4);
//        }

        // TODO: voir si besoin de mettre crisp ici
        flash('Le statut a bien été mis à jour')->success();
        return back();
    }

    public function index()
    {
        return view('Pressing/home');
    }

    public function getOrders(Request $request): array
    {
        $providerId = session('authId');

        if (isset($request->type)) {
            switch ($request->type) {
                case $type = 'finished':
                case $type = 'processing':
                case $type = 'pickupDone';
                    $order = Pressing::getProviderOrder($providerId, $this->status[$type]);
                    break;
                default:
                    $order = Pressing::getProviderOrder($providerId, $this->status['default']);
                    break;
            }
        } else {
            $order = Pressing::getProviderOrder($providerId, $this->status['default']);
        }

        return isset($order) ? $order : [];
    }

}
