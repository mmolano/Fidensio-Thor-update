<?php

namespace App\Services\Crisp;

use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;

class CrispFake
{
    const GENDER = [
        0 => 'male',
        1 => 'male',
        2 => 'female'
    ];

    private Generator $faker;
    private array $userlists = [];

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function createUser(array $user): ?string
    {
        $oldUserCrispId = null;

        foreach ($this->userlists as $key => $userCrisp) {
            if ($userCrisp['email'] == $user['email']) {
                $oldUserCrispId = $userCrisp['crispId'];
                unset($this->userlists[$key]);
            }
        }

        $user = [
            'crispId' => $oldUserCrispId ? $oldUserCrispId : $this->faker->password,
            'email' => $user['email'],
            'person' => [
                'nickname' => $user['data']['firstName'] . ' ' . $user['data']['lastName'],
                'gender' => self::GENDER[$user['data']['gender']],
                'phone' => '+' . $user['indicMobile'] . $user['mobile']
            ],
            'company' => [
                'name' => 'test',
                'url' => ''
            ],
            'data' => [
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
            ]
        ];

        array_push($this->userlists, $user);

        return $user['crispId'];
    }

    public function getUserData(array $user): ?array
    {
        foreach ($this->userlists as $userCrisp) {
            if ($userCrisp['crispId'] == $user['integration']['crispId']) {
                return $userCrisp['data'];
            }
        }

        return null;
    }

    public function updateUserData(array $user, array $data): bool
    {
        foreach ($this->userlists as $key => $userCrisp) {
            if ($userCrisp['crispId'] == $user['integration']['crispId']) {
                $data = array_merge($userCrisp['data'], $data);
                $userCrispUpdate = array_merge($this->userlists[$key], ['data' => $data]);
                $this->userlists[$key] = $userCrispUpdate;

                return true;
            }
        }

        return false;
    }

    public function newEvent(array $user, string $titleType, string $prestataire, array $order = [], int $finalPrice = 0): bool
    {
        return true;
    }
}
