<?php

namespace Tests\Unit;

use App\Facades\Stripe;
use Tests\TestCase;

class StripeTest extends TestCase
{
    /*
     * Methode getDefaultCard
     */
    /**
     * @test
     */
    public function getDefaultCardWithBadStripeCardId()
    {
        $user = [
            'email' => $this->faker->email
        ];
        $userData = [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName
        ];
        $userIntegration = [
            'stripeId' => Stripe::createUser($user, $userData)
        ];

        Stripe::newCard($userIntegration, $this->faker->password);
        $response = Stripe::getDefaultCard($userIntegration);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function getDefaultCardWithoutCard()
    {
        $user = [
            'email' => $this->faker->email
        ];
        $userData = [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName
        ];
        $userIntegration = [
            'stripeId' => Stripe::createUser($user, $userData)
        ];

        $response = Stripe::getDefaultCard($userIntegration);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function getDefaultCard()
    {
        $user = [
            'email' => $this->faker->email
        ];
        $userData = [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName
        ];
        $userIntegration = [
            'stripeId' => Stripe::createUser($user, $userData)
        ];

        $card = Stripe::newCard($userIntegration, 'tok_visa');
        $response = Stripe::getDefaultCard($userIntegration);

        $this->assertNotNull($response);
        $this->assertEquals($card['id'], $response['id']);
        $this->assertEquals($card['name'], $response['name']);
        $this->assertEquals($card['default'], $response['default']);
        $this->assertEquals($card['last4'], $response['last4']);
        $this->assertEquals($card['expireMonth'], $response['expireMonth']);
        $this->assertEquals($card['expireYear'], $response['expireYear']);
    }

    /*
     * Methode pay
     */
    /**
     * @test
     */
    public function createPaymentWithoutCard()
    {
        $user = [
            'email' => $this->faker->email
        ];
        $userData = [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName
        ];
        $userIntegration = [
            'stripeId' => Stripe::createUser($user, $userData)
        ];
        $order = [
            'id' => $this->faker->numberBetween(100, 999999),
            'amount' => 1000
        ];
        $user = [
            'integration' => [
                'stripeId' => $userIntegration['stripeId']
            ]
        ];

        $response = Stripe::pay($order, $user);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function createPaymentWithFailurePayment()
    {
        $user = [
            'email' => $this->faker->email
        ];
        $userData = [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName
        ];
        $userIntegration = [
            'stripeId' => Stripe::createUser($user, $userData)
        ];
        $order = [
            'id' => $this->faker->numberBetween(100, 999999),
            'amount' => $this->faker->numberBetween(100, 300)
        ];
        $user = [
            'integration' => [
                'stripeId' => $userIntegration['stripeId']
            ]
        ];

        Stripe::newCard($userIntegration, 'tok_chargeDeclinedInsufficientFunds');

        $response = Stripe::pay($order, $user);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function createPaymentWith3ds()
    {
        $user = [
            'email' => $this->faker->email
        ];
        $userData = [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName
        ];
        $userIntegration = [
            'stripeId' => Stripe::createUser($user, $userData)
        ];
        $order = [
            'id' => $this->faker->numberBetween(100, 999999),
            'amount' => $this->faker->numberBetween(100, 300)
        ];
        $user = [
            'integration' => [
                'stripeId' => $userIntegration['stripeId']
            ]
        ];

        Stripe::newCard($userIntegration, 'tok_threeDSecure2Required');

        $response = Stripe::pay($order, $user);

        $this->assertNotNull($response);
        $this->assertEquals(2, $response['status']);
        $this->assertEquals('Authentication required', $response['message']);
    }

    /**
     * @test
     */
    public function payOrder()
    {
        $user = [
            'email' => $this->faker->email
        ];
        $userData = [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName
        ];
        $userIntegration = [
            'stripeId' => Stripe::createUser($user, $userData)
        ];
        $order = [
            'id' => $this->faker->numberBetween(100, 999999),
            'amount' => $this->faker->numberBetween(100, 300)
        ];
        $user = [
            'integration' => [
                'stripeId' => $userIntegration['stripeId']
            ]
        ];

        Stripe::newCard($userIntegration, 'tok_visa');

        $response = Stripe::pay($order, $user);

        $this->assertNotNull($response);
        $this->assertEquals(1, $response['status']);
        $this->assertEquals('Payment success', $response['message']);
    }
}
