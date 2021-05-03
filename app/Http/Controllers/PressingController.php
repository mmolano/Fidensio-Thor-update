<?php

namespace App\Http\Controllers;

use App\Facades\Stripe;
use App\Services\Pressing\Pressing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PressingController extends Controller
{
    private ?int $error = null;
    private array $status = [
        'default' => 1,
        'pickupDone' => 2,
        'processing' => 3,
        'finished' => 5,
        'waitingForPayment' => 7
    ];

    private function error(?int $error, ?string $infos = null, string $status = 'error', int $errorCode = 400, ?string $message = null): JsonResponse
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
                Log::error('MyError', [
                    'Class' => class_basename(self::class),
                    'Code' => $this->error,
                    'validationErrors' => json_decode($infos),
                    'Comment' => 'Impossible de récupérer certaines informations sur la commande'
                ]);
                break;
            case 4:
                $message = 'Impossible de changer le statut de la commande';
                break;
            case 5:
                $message = 'Impossible d\'obtenir les informations de la commande';
                break;
            case 6:
                $message = 'Impossible d\'obtenir les informations du produit';
                Log::error('MyError', [
                    'Class' => class_basename(self::class),
                    'Code' => $this->error,
                    'ProductName' => $infos,
                    'Comment' => 'Impossible de récupérer certaines informations sur la commande'
                ]);
                break;
            case 7:
                $message = 'Impossible d\'ajouter des détails à la commande';
                Log::error('MyError', [
                    'Class' => class_basename(self::class),
                    'Code' => $this->error,
                    'ProductName' => $infos,
                    'Comment' => 'Impossible d\'ajouter des détails à la commande'
                ]);
                break;
            case 8:
                $message = 'Impossible de finaliser la procédure de paiement mais détails ajoutés';
                Log::error('MyError', [
                    'Class' => class_basename(self::class),
                    'Code' => $this->error,
                    'OrderId' => $infos,
                    'Comment' => 'Erreur lors du paiement'
                ]);
                break;
            case 9:
                $message = 'Un paiement  3DS est requis';
                Log::error('MyError', [
                    'Class' => class_basename(self::class),
                    'Code' => $this->error,
                    'OrderId' => $infos,
                    'Comment' => '3DS pay required'
                ]);
                break;
            case 10:
                $message = 'Impossible d\'obtenir les informations du client';
                break;
            case 11:
                $message = 'Impossible d\'obtenir les produits';
                Log::error('MyError', [
                    'Class' => class_basename(self::class),
                    'Code' => $this->error,
                    'Comment' => 'Erreur lors de l\'obtention des produits'
                ]);
                break;
            case 12:
                $message = 'Une erreur est survenue, impossible d\'envoyer les details';
                Log::error('MyError', [
                    'Class' => class_basename(self::class),
                    'Code' => $this->error,
                    'validationErrors' => json_decode($infos),
                    'Comment' => 'Impossible de récupérer les details'
                ]);
                break;
            case 13:
                $message = 'Une erreur est survenue, impossible de modifier le statut du paiement';
                Log::error('MyError', [
                    'Class' => class_basename(self::class),
                    'Code' => $this->error,
                    'OrderId' => $infos,
                    'Comment' => 'Impossible d\'update le statut du paiement'
                ]);
                break;
            default:
                $message ?: $message = 'Undefined error';
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $errorCode);
    }

    public function commitStatus(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'orderId' => ['required', 'integer'],
            'status' => ['required', 'integer']
        ]);

        if ($validation->fails()) {
            return $this->error(3, json_encode($validation->errors()));
        }

        if (!Pressing::changeStatus($request->orderId, $request->status)) {
            return $this->error(4);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'La commande a été mis à jour',
        ]);
    }

    public function pay(array $order, array $user, bool $changeStatus = false)
    {
        if ($order['payment']['pay'] !== 1) {
            if (!$payment = Stripe::pay($order, $user)) {
        return $payment;
                if ($changeStatus === true) {
                    if (!Pressing::changeStatus($order['id'], $this->status['processing'])) {
                        return $this->error(4);
                    }

                    return $this->error(8, $order['id'], 'warning', 200);
                }
                return $payment;
                return $this->error(8, $order['id']);
            } elseif ($payment['status'] === 2) {
                if (!Pressing::updatePayment($order['id'], [
                    'status' => $payment['status'],
                    'intentStripeId' => $payment['intentId'],
                    'paymentToken3ds' => $payment['paymentToken'],
                ])) {
                    if ($changeStatus === true) {
                        if (!Pressing::changeStatus($order['id'], $this->status['waitingForPayment'])) {
                            return $this->error(4);
                        }
                        return $this->error(9, $order['id'], 'warning', 200);
                    }

                    // TODO: send email

                    return $this->error(9, $order['id'], 'warning');
                }
            } elseif ($payment['status'] === 1) {
                if (!Pressing::updatePayment($order['id'], [
                    'status' => $payment['status'],
                    'intentStripeId' => $payment['intentId']
                ])) {
                    return $this->error(13, null, 'warning');
                }

                if ($changeStatus === true) {
                    if (!Pressing::changeStatus($order['id'], $this->status['processing'])) {
                        return $this->error(4);
                    }
                } else {
                    if (!Pressing::changeStatus($order['id'], $this->status['finished'])) {
                        return $this->error(4);
                    }
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Le paiement a été accepté',
        ]);
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
                case $type = 'pickupDone':
                    $order = Pressing::getProviderOrder($providerId, $this->status[$type]);
                    break;
                case $type = 'processing':
                    $orderProcess = Pressing::getProviderOrder($providerId, $this->status[$type]);
                    $orderWaiting = Pressing::getProviderOrder($providerId, $this->status['waitingForPayment']);
                    $order = array_merge($orderProcess, $orderWaiting);
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

    public function getProducts(Request $request)
    {
        $products = Pressing::getProviderProduct($request->id);

        return isset($products) ? $products : $this->error(5);
    }

    public function reHandlePayment(Request $request): JsonResponse
    {
        if (!$order = Pressing::getOrder($request->id)) {
            return $this->error(5);
        } elseif (!$user = Pressing::getUser($order['userId'])) {
            return $this->error(10);
        }

        return self::pay($order, $user);
    }

    public function processPayment(Request $request): JsonResponse
    {
        if (!$order = Pressing::getOrder($request->id)) {
            return $this->error(5);
        } elseif (!$user = Pressing::getUser($order['userId'])) {
            return $this->error(10);
        } elseif (!$products = Pressing::getProviderProduct($user['id'])) {
            return $this->error(11);
        } elseif ($request->details) {
            foreach ($request->details as $detail) {
                $success = false;
                foreach ($products['products'] as $product) {
                    if ($detail['id'] === $product['id']) {
                        $success = true;
                        break;
                    }
                }

                if (!$success) {
                    return $this->error(6, $detail['name']);
                } elseif ($detail['quantity'] < 1) {
                    return $this->error(6, $detail['name']);
                }

                if (!Pressing::postDetail($order['id'], [
                    'name' => $detail['name'],
                    'isNegative' => 0,
                    'isPercent' => 0,
                    'quantity' => $detail['quantity'] * 100,
                    'price' => $detail['price'],
                    'total' => $detail['finalPrice']
                ])) {
                    return $this->error(7, $detail['name']);
                }
            }

            //TODO: faire une route sur ouranos qui update ça: + champ dans le front
//            $order->Pressing()->update([
//                'providerComment' => $request->comment,
//                'providerOrderNumber' => $request->numberPress
//            ]);
        }

        //TODO HANDLE le gift

        return self::pay($order, $user, true);
    }
}
