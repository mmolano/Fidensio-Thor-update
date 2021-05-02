<?php

namespace App\Services\Pressing;

class PressingFake
{
    private array $providers = [
        0 => [
            'id' => 1,
            'name' => 'Johan',
            'email' => 'ok@test.com',
            'password' => 'da'
        ],
        1 => [
            'id' => 2,
            'name' => 'Ravier',
            'email' => 'ravier@gmail.com',
            'password' => ''
        ],
        2 => [
            'id' => 3,
            'name' => 'Fidensio',
            'email' => 'test@fidensio.com',
            'password' => 'ok'
        ]
    ];

    private array $allowedId = [
        'order' => [1, 2, 3],
        'status' => [1, 2, 3, 4, 5, 6, 7]
    ];

    private static function checkIfExist(array $providers, string $email, string $password): ?array
    {
        foreach ($providers as $provider) {
            if ($provider['email'] === $email && $provider['password'] === $password) {
                return [
                    'id' => $provider['id'],
                    'name' => $provider['name'],
                ];
            }
        }

        return null;
    }

    public function checkProvider(string $email, ?string $password = null): ?array
    {
        if (!$user = self::checkIfExist($this->providers, $email, $password)) {
            return null;
        }

        return $user;
    }

    public function changeStatus(int $orderId, int $statusId): ?array
    {
        if (empty($this->allowedId['order'][$orderId]) || empty($this->allowedId['status'][$statusId])) {
            return null;
        }

        return [
            'id' => $orderId,
            'userId' => 2,
            'companyId' => 1,
            'serviceId' => 4,
            'providerId' => 1,
            'status' => $statusId,
            'userComment' => 'Test PressingAtCompany',
            'amount' => '3027',
            'deliveryDate' => '2021-05-03 12:04:12',
            'createdAt' => '2021-04-30 12:04:12',
            'updatedAt' => '2021-04-30 12:04:12'
        ];
    }
}
