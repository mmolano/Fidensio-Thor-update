<?php

namespace App\Services\Crisp;

use Carbon\Carbon;
use Crisp\CrispClient;

class Crisp
{
    const GENDER = [
        0 => 'male',
        1 => 'male',
        2 => 'female'
    ];

    private CrispClient $client;
    private $appId;
    private array $titles = [
        'received' => 'Le préstataire vient de recupérer la commande',
        'delivered' => 'La commande a été livrée',
        'payment' => 'Le préstataire vient d\'encaisser la commande'
    ];

    public function __construct()
    {
        $this->client = new CrispClient();
        $this->client->authenticate(env('CRISP_PUBLIC'), env('CRISP_SECRET'));

        $this->appId = env('CRISP_APP_ID');
    }

    private function checkExist(string $email): ?string
    {
        try {
            $userCrisp = $this->client->websitePeople->findByEmail($this->appId, $email);
        } catch (\Exception $exception) {
            return null;
        }

        return $userCrisp['people_id'];
    }

    public function createUser(array $user): ?string
    {
        $params = [
            'email' => $user['email'],
            'person' => [
                'nickname' => $user['data']['firstName'] . ' ' . $user['data']['lastName'],
                'gender' => self::GENDER[$user['data']['gender']],
                'phone' => '+' . $user['indicMobile'] . $user['mobile']
            ],
            'company' => [
                'name' => 'Test',
                'url' => ''
            ]
        ];

        $data = [
            'userId' => $user['id'],
            'firstName' => $user['data']['firstName'],
            'lastName' => $user['data']['lastName'],
            'dateOfBirth' => $user['data']['dateOfBirth'] ? Carbon::create($user['data']['dateOfBirth'])->format('Ymd') : 0,
            'creditCard' => 'false',
            'creditCardExpirationCountDown' => 0,
            'numberOfOrders' => 0,
            'lastOrderDateCount' => 0,
            'lastLoginCount' => 0,
            'createdDateCount' => 0,
            'lastSatisfaction' => 0,
            'referralCode' => '',
            'lastCoupon' => ''
        ];

        if ($crispId = $this->checkExist($user['email'])) {
            try {
                $this->client->websitePeople->updatePeopleProfile($this->appId, $crispId, $params);
                $this->client->websitePeople->updatePeopleData($this->appId, $crispId, ['data' => $data]);

                $response['people_id'] = $crispId;
            } catch (\Exception $exception) {
                return null;
            }
        } else {
            try {
                $response = $this->client->websitePeople->createNewPeopleProfile($this->appId, $params);
                $this->client->websitePeople->updatePeopleData($this->appId, $response['people_id'], ['data' => $data]);
            } catch (\Exception $exception) {
                return null;
            }
        }

        return $response['people_id'];
    }

    public function getUserData(array $user): ?array
    {
        try {
            $userDataCrisp = $this->client->websitePeople->getPeopleData($this->appId, $user['integration']['crispId']);
        } catch (\Exception $exception) {
            return null;
        }

        return $userDataCrisp['data'];
    }

    public function updateUserData(array $user, array $data): bool
    {
        if (!$oldData = $this->getUserData($user)) {
            return false;
        }

        try {
            $this->client->websitePeople->updatePeopleData($this->appId, $user['integration']['crispId'], [
                'data' => array_merge($oldData, $data)
            ]);
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    public function newEvent(array $user, string $titleType, string $prestataire, array $order = [], int $finalPrice = 0): bool
    {
        $event = [
            'text' => $this->titles[$titleType],
        ];

        if (!empty($order)) {
            $event['data'] = [
                'NumeroDeCommande' => $order['id'],
                'IdUtilisateur' => $order['userId'],
                'MailUtilisateur' => $order['user']['email'],
                'MobileUtilisateur' => $order['user']['mobile'],
                'Service' => $order['service']['name'],
                'Prestataire' => $prestataire,
                'Lockers' => !empty($order['locker']) ? $order['locker']['number'] : 'BringMe',
                'CodeLockers' => !empty($order['locker']) ? $order['locker']['number'] : 'Aucun',
                'DateCreation' => $order['createdAt'] ? Carbon::create($order['createdAt'])->format('d-m-Y H:i:s') : 'Null'
            ];

            switch ($titleType) {
                case 'delivered':
                    $mergeData = [
                        'DateDeLivraison' => Carbon::now()->format('d-m-Y H:i:s')
                    ];
                    $event['data'] = array_merge($event['data'], $mergeData);
                    break;
                case 'payment':
                    $dataUserCrisp = $this->getUserData($user);
                    $mergeData = [
                        'IncrementationOrderTotal' => $finalPrice !== 0 ? $finalPrice : 'Null',
                        'DateDeLaCommande' => Carbon::now()->format('d-m-Y H:i:s')
                    ];
                    $event['data'] = array_merge($event['data'], $mergeData);

                    $this->updateUserData($user, [
                        'MontantDesCommandes' . $order['serviceCategory']['name'] => isset($dataUserCrisp['MontantDesCommandes' . $order['serviceCategory']['name']])? $dataUserCrisp['MontantDesCommandes' . $order['serviceCategory']['name']] + $finalPrice: $finalPrice,
                        'MontantDesCommandesTotal' => isset($dataUserCrisp['MontantDesCommandes' . $order['serviceCategory']['name']]) ? $dataUserCrisp['MontantDesCommandesTotal'] + $finalPrice : $finalPrice,
                        'DateDerniereCommande' => Carbon::now()->format('d-m-Y H:i:s'),
                    ]);
                    break;
                default:
                    break;
            }
        }

        try {
            $this->client->websitePeople->addPeopleEvent($this->appId, $user['integration']['crispId'], $event);
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }
}
