<?php

namespace Tests\Unit;

use App\Facades\Crisp;
use Carbon\Carbon;
use Tests\TestCase;

class CrispTest extends TestCase
{
    /*
     * Methode createUser
     */
    /**
     * @test
     */
    public function createUserWithUserAlreadyExist()
    {
        $userA = [
            'id' => 1,
            'email' => $this->faker->email,
            'indicMobile' => 33,
            'mobile' => $this->faker->phoneNumber,
            'data' => [
                'firstName' => $this->faker->firstName,
                'lastName' => $this->faker->lastName,
                'dateOfBirth' => '1997-10-02',
                'gender' => $this->faker->numberBetween(1, 2)
            ],
        ];

        $userIntegrationA = [
            'integration' => [
                'crispId' => Crisp::createUser($userA)
            ]
        ];

        $userA = array_merge($userA, $userIntegrationA);

        $userB = [
            'id' => 2,
            'email' => $userA['email'],
            'indicMobile' => 33,
            'mobile' => $this->faker->phoneNumber,
            'data' => [
                'firstName' => $this->faker->firstName,
                'lastName' => $this->faker->lastName,
                'dateOfBirth' => '1997-10-02',
                'gender' => $this->faker->numberBetween(1, 2)
            ],
        ];

        $userIntegrationB = [
            'integration' => [
                'crispId' => Crisp::createUser($userB)
            ]
        ];

        $userB = array_merge($userB, $userIntegrationB);

        $response = Crisp::getUserData($userA);

        $this->assertNotNull($response);
        $this->assertEquals($userB['data']['firstName'], $response['firstName']);
        $this->assertEquals($userB['data']['lastName'], $response['lastName']);
    }

    /**
     * @test
     */
    public function createUser()
    {
        $user = [
            'id' => 2,
            'email' => $this->faker->email,
            'indicMobile' => 33,
            'mobile' => $this->faker->phoneNumber,
            'data' => [
                'firstName' => $this->faker->firstName,
                'lastName' => $this->faker->lastName,
                'dateOfBirth' => '1997-10-02',
                'gender' => $this->faker->numberBetween(1, 2)
            ],
        ];

        $userIntegration = [
            'integration' => [
                'crispId' => Crisp::createUser($user)
            ]
        ];

        $user = array_merge($user, $userIntegration);

        $response = Crisp::createUser($user);

        $this->assertNotNull($response);
    }

    /*
     * Methode getUserData
     */
    /**
     * @test
     */
    public function getUserData()
    {
        $user = [
            'id' => 2,
            'email' => $this->faker->email,
            'indicMobile' => 33,
            'mobile' => $this->faker->phoneNumber,
            'data' => [
                'firstName' => $this->faker->firstName,
                'lastName' => $this->faker->lastName,
                'dateOfBirth' => '1997-10-02',
                'gender' => $this->faker->numberBetween(1, 2)
            ],
        ];

        $userIntegration = [
            'integration' => [
                'crispId' => Crisp::createUser($user)
            ]
        ];

        $user = array_merge($user, $userIntegration);

        $response = Crisp::getUserData($user);

        $this->assertNotNull($response);
        $this->assertEquals($user['data']['firstName'], $response['firstName']);
        $this->assertEquals($user['data']['lastName'], $response['lastName']);
    }

    /*
     * Methode updateUserData
     */
    /**
     * @test
     */
    public function updateUserDataWithoutUser()
    {
        $user = [
            'id' => 2,
            'email' => $this->faker->email,
            'indicMobile' => 33,
            'mobile' => $this->faker->phoneNumber,
            'data' => [
                'firstName' => $this->faker->firstName,
                'lastName' => $this->faker->lastName,
                'dateOfBirth' => '1997-10-02',
                'gender' => $this->faker->numberBetween(1, 2)
            ],
            'integration' => [
                'crispId' => null
            ]
        ];

        $response = Crisp::updateUserData($user, [
            'numberOfOrders' => 0
        ]);

        $this->assertFalse($response);
    }

    /**
     * @test
     */
    public function updateUserData()
    {
        $user = [
            'id' => 2,
            'email' => $this->faker->email,
            'indicMobile' => 33,
            'mobile' => $this->faker->phoneNumber,
            'data' => [
                'firstName' => $this->faker->firstName,
                'lastName' => $this->faker->lastName,
                'dateOfBirth' => '1997-10-02',
                'gender' => $this->faker->numberBetween(1, 2)
            ],
        ];

        $userIntegration = [
            'integration' => [
                'crispId' => Crisp::createUser($user)
            ]
        ];

        $user = array_merge($user, $userIntegration);

        $response = Crisp::updateUserData($user, [
            'numberOfOrders' => 1
        ]);

        $this->assertTrue($response);

        $userDataCrisp = Crisp::getUserData($user);
        $this->assertEquals(1, $userDataCrisp['numberOfOrders']);
    }

    /*
     * Methode newEvent
     */
    /**
     * @test
     */
    public function newEventWithoutData()
    {
        $user = [
            'id' => 2,
            'email' => $this->faker->email,
            'indicMobile' => 33,
            'mobile' => $this->faker->phoneNumber,
            'data' => [
                'firstName' => $this->faker->firstName,
                'lastName' => $this->faker->lastName,
                'dateOfBirth' => '1997-10-02',
                'gender' => $this->faker->numberBetween(1, 2)
            ],
        ];

        $userIntegration = [
            'integration' => [
                'crispId' => Crisp::createUser($user)
            ]
        ];

        $user = array_merge($user, $userIntegration);

        $this->assertTrue(Crisp::newEvent($user, 'Event de test', 'Pressing'));
    }

    /**
     * @test
     */
    public function newEventWithData()
    {
        $user = [
            'id' => 2,
            'email' => $this->faker->email,
            'indicMobile' => 33,
            'mobile' => $this->faker->phoneNumber,
            'data' => [
                'firstName' => $this->faker->firstName,
                'lastName' => $this->faker->lastName,
                'dateOfBirth' => '1997-10-02',
                'gender' => $this->faker->numberBetween(1, 2)
            ],
        ];

        $userIntegration = [
            'integration' => [
                'crispId' => Crisp::createUser($user)
            ]
        ];

        $user = array_merge($user, $userIntegration);

        $order = [
            'id' => 1,
            'userId' => $user['id'],
            'user' => [
                'email' => $user['email'],
                'mobile' => $this->faker->phoneNumber
            ],
            'service' => [
                'name' => 'Pressing'
            ],
            'locker' => [],
            'createdAt' => "2021-04-30T12:03:53.000000Z"
        ];

        $this->assertTrue(Crisp::newEvent($user, 'delivered', 'Pressing', $order));
    }

    /**
     * @test
     */
    public function newEventWithPayData()
    {
        $user = [
            'id' => 2,
            'email' => $this->faker->email,
            'indicMobile' => 33,
            'mobile' => $this->faker->phoneNumber,
            'data' => [
                'firstName' => $this->faker->firstName,
                'lastName' => $this->faker->lastName,
                'dateOfBirth' => '1997-10-02',
                'gender' => $this->faker->numberBetween(1, 2)
            ],
        ];

        $userIntegration = [
            'integration' => [
                'crispId' => Crisp::createUser($user)
            ]
        ];

        $user = array_merge($user, $userIntegration);

        $order = [
            'id' => 1,
            'userId' => $user['id'],
            'user' => [
                'email' => $user['email'],
                'mobile' => $this->faker->phoneNumber
            ],
            'service' => [
                'name' => 'Pressing'
            ],
            'serviceCategory' => [
                'id' => 1,
                'name' => 'Livraison à domicile',
                'categoryId' => 1,
            ],
            'locker' => [],
            'createdAt' => "2021-04-30T12:03:53.000000Z"
        ];

        $this->assertTrue(Crisp::newEvent($user, 'payment', 'Pressing', $order, 800));
    }
}
