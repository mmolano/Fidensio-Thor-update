<?php

namespace App\Http\Controllers;

use App\Facades\Mailjet;
use App\Facades\Stripe;
use App\Services\Pressing\Pressing;
use Carbon\Carbon;
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
                $message = 'Impossible de finaliser la procédure de paiement';
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
                    'Comment' => 'paiement 3ds nécessaire'
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
            case 14:
                $message = 'Une erreur est survenue';
                Log::error('MyError', [
                    'Class' => class_basename(self::class),
                    'Code' => $this->error,
                    'Infos' => $infos,
                    'Comment' => 'Erreur lors de la modification du commentaire et du numéro'
                ]);
                break;
            case 15:
                $message = 'Impossible d\'envoyer un mail au client';
                Log::error('MyError', [
                    'Class' => class_basename(self::class),
                    'Code' => $this->error,
                    'OrderId' => $infos,
                    'Comment' => 'Erreur lors de l\'envoie du mail au client'
                ]);
                break;
            case 16:
                $message = 'Impossible d\'obtenir les produits, veuillez nous contacter';
                Log::error('MyError', [
                    'Class' => class_basename(self::class),
                    'Code' => $this->error,
                    'Comment' => 'Erreur lors de la récupération des produits'
                ]);
                break;
            case 17:
                $message = 'Impossible de mettre à jour les informations du casier';
                Log::error('MyError', [
                    'Class' => class_basename(self::class),
                    'Code' => $this->error,
                    'infos' => $infos,
                    'Comment' => 'Impossible de mettre à jour le locker du produit'
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
            'status' => ['required', 'integer'],
        ]);

        if ($validation->fails()) {
            return $this->error(3, json_encode($validation->errors()));
        }

        if (!Pressing::changeStatus($request->orderId, $request->status)) {
            return $this->error(4);
        }

        if (!$order = Pressing::getOrder($request->orderId)) {
            return $this->error(5);
        } elseif (!$user = Pressing::getUser($order['userId'])) {
            return $this->error(10);
        }

        $userData = ['email' => $user['email'], 'name' => $user['data']['firstName']];

        switch ($request->status) {
            case 2:
                if (!empty($order['locker'])) {
                    if (!Pressing::updateLocker($order['companyId'], [
                        'orderId' => null,
                        'lockerCode' => null,
                        'number' => $order['locker']['number']
                    ])) {
                        return $this->error(17);
                    }
                }
                break;
            case 5:
                if ($order['payment']['pay'] === 0) {
                    self::pay($order, $user);
                }
                if ($order['company']['lockersType'] === 1) {
                    if (!$locker = Pressing::updateLocker($order['companyId'], [
                        'orderId' => $order['id'],
                        'lockerCode' => $request->lockerCode,
                        'number' => $request->number
                    ])) {
                        return $this->error(17, json_encode($locker));
                    }

                    $subject = 'Votre commande Fidensio n°' . $order['id'] . ' est disponible dans le casier ' .
                        $locker['number'] .
                        ' avec le code C' .
                        $locker['code'] .
                        ' pour l’ouvrir.';
                    $templateType = 'order_completed_oldLockers';
                    $variables = [
                        'orderId' => $order['id'],
                        'service' => $order['service']['name'],
                        'lockerNumber' => $locker['number'],
                        'lockerCode' => 'C' . $locker['code'],
                        'url' => 'https://www.connexion.fidensio.com/',
                        'deliveryDate' => Carbon::create($order['deliveryDate'])->format('d/m/Y')
                    ];
                } else {
                    $subject = 'Votre commande Fidensio n°' . $order['id'] . ' est disponible dans votre lockers Bringme. Scannez le QR code reçu par mail.';
                    $templateType = 'order_completed_bringMe';
                    $variables = [
                        'orderId' => $order['id'],
                        'service' => $order['service']['name'],
                        'url' => 'https://www.connexion.fidensio.com/',
                        'deliveryDate' => Carbon::create($order['deliveryDate'])->format('d/m/Y')
                    ];
                }
                if (!Mailjet::sendWithTemplate($userData, $templateType, $subject, $variables)) {
                    return $this->error(15, $order['id'], 'warning');
                }

                break;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'La commande a été mis à jour',
        ]);
    }

    public function pay(array $order, array $user, bool $changeStatus = false): JsonResponse
    {
        $userData = ['email' => $user['email'], 'name' => $user['data']['firstName']];

        $params = [
            'orderId' => $order['id'],
            'service' => $order['service']['name'],
            'firstName' => $user['data']['firstName'],
            'lastName' => $user['data']['lastName'],
            'email' => $user['email'],
            'mobile' => '+' . $user['indicMobile'] . $user['mobile'],
            'amountWithoutVAT' => $order['amount'] / 100,
            'amount' => $order['amount'] / 100,
            'products' => [],
        ];

        if (!empty($order['details'])) {
            foreach ($order['details'] as $key => $value) {
                $values[] = [
                    'name' => $value['name'],
                    'quantity' => $value['quantity'],
                    'total' => $value['total'],
                ];
                $params['products'] = array_replace_recursive($values, $params['products']);
            }
        }

        if ($order['amount'] > 0) {
            if ($order['payment']['pay'] !== 1) {
                if (!$payment = Pressing::pay($order['id'])) {
                    if (!Pressing::changeStatus($order['id'], $this->status['processing'])) {
                        return $this->error(4);
                    } elseif (!Mailjet::sendWithTemplate($userData, 'payment_refused', 'Le paiement pour votre commande n°' . $order['id'] . ' a été refusé')) {
                        return $this->error(15, $order['id'], 'warning');
                    }
                    return $this->error(8, $order['id'], 'warning', 200);
                } else {
                    switch ($payment['payment']['pay']) {
                        case 1:
                            if (!Mailjet::sendWithTemplate($userData, 'payment_confirmed', 'Le paiement pour votre commande n°' . $order['id'] . ' a été validé', $params)) {
                                return $this->error(15, $order['id'], 'warning');
                            }
                            $hasBeenSent = true;
                            break;
                        case 0 && ($payment['payment']['paymentToken3ds'] !== null):
                            if (!Pressing::changeStatus($order['id'], $this->status['waitingForPayment'])) {
                                return $this->error(4);
                            } elseif (!Mailjet::sendWithTemplate($userData, 'payment_3DSecure', 'Le paiement pour votre commande n°' . $order['id'] . ' nécessite votre intervention')) {
                                return $this->error(15, $order['id'], 'warning');
                            }
                            return $this->error(9, $order['id'], 'warning', 200);
                        default:
                            return response()->json([
                                'status' => 'warning',
                                'message' => 'Le paiement a déjà été effectué',
                            ]);
                    }
                }
            }
        }

        if ($changeStatus) {
            if (!Pressing::changeStatus($order['id'], $this->status['processing'])) {
                return $this->error(4);
            } elseif (!isset($hasBeenSent)) {
                if (!Mailjet::sendWithTemplate($userData, 'payment_confirmed', 'Le paiement pour votre commande n°' . $order['id'] . ' a été validé', $params)) {
                    return $this->error(15, $order['id'], 'warning');
                }
            }
        } else {
            if (!Pressing::changeStatus($order['id'], $this->status['finished'])) {
                return $this->error(4);
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
                    $order = Pressing::getProviderOrder($providerId, $this->status[$type], $request->page, true);
                    break;
                case $type = 'pickupDone':
                    $order = Pressing::getProviderOrder($providerId, $this->status[$type]);
                    break;
                case $type = 'processing':
                    $order = Pressing::getProviderOrder($providerId, $this->status['waitingForPayment'] . ',' . $this->status[$type]);
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

        return isset($products) ? $products : $this->error(16);
    }

    public function reHandlePayment(Request $request): JsonResponse
    {
        if (!$order = Pressing::getOrder($request->id)) {
            return $this->error(5);
        } elseif (!$user = Pressing::getUser($order['userId'])) {
            return $this->error(10);
        }

        return self::pay($order, $user, true);
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
                } elseif (!Pressing::postDetail($order['id'], [
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
        }

        if ($request->comment || $request->numberPress) {
            if (!Pressing::updateOrderAttributes($request->id, [
                'providerOrderNumber' => isset($request->numberPress) ? $request->numberPress : '',
                'providerComment' => isset($request->comment) ? $request->comment : '',
            ])) {
                return $this->error(14, json_encode('code: ' . $request->numberPress . ', comment:' . $request->comment . ', orderId: ' . $request->id));
            }
        }

        if (!$order = Pressing::getOrder($request->id)) {
            return $this->error(5);
        }

        return self::pay($order, $user, true);
    }
}
