<?php

namespace Tests\Unit;

use App\Facades\Stripe;
use Carbon\Carbon;
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
        $cardType = 'test_token';

        $user = [
            'userId' => 1,
            'stripeId' => $cardType,
            'crispId' => 'cus_token'
        ];

        Stripe::setCardId($cardType);
        $response = Stripe::getDefaultCard($user);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function getDefaultCardVisa()
    {
        $cardType = 'tok_visa';

        $user = [
            'userId' => 1,
            'stripeId' => $cardType,
            'crispId' => 'cus_token'
        ];

        Stripe::setCardId($cardType);
        $response = Stripe::getDefaultCard($user);

        $this->assertNotNull($response);
        $this->assertEquals($cardType, $response['id']);
        $this->assertEquals($cardType, $response['name']);
        $this->assertEquals(true, $response['default']);
        $this->assertEquals(4242, $response['last4']);
    }

    /**
     * @test
     */
    public function getDefaultCardMasterCard()
    {
        $cardType = 'tok_mastercard';

        $user = [
            'userId' => 1,
            'stripeId' => $cardType,
            'crispId' => 'cus_token'
        ];

        Stripe::setCardId($cardType);
        $response = Stripe::getDefaultCard($user);

        $this->assertNotNull($response);
        $this->assertEquals($cardType, $response['id']);
        $this->assertEquals($cardType, $response['name']);
        $this->assertEquals(true, $response['default']);
        $this->assertEquals(4444, $response['last4']);
    }

    /**
     * @test
     */
    public function getDefaultCardAmex()
    {
        $cardType = 'tok_amex';

        $user = [
            'userId' => 1,
            'stripeId' => $cardType,
            'crispId' => 'cus_token'
        ];

        Stripe::setCardId($cardType);
        $response = Stripe::getDefaultCard($user);

        $this->assertNotNull($response);
        $this->assertEquals($cardType, $response['id']);
        $this->assertEquals($cardType, $response['name']);
        $this->assertEquals(true, $response['default']);
        $this->assertEquals(8431, $response['last4']);
    }

    /*
     * Methode pay
     */

    /**
     * @test
     */
    public function createPaymentWithFailurePayment()
    {
        $cardType = 'tok_chargeCustomerFail';

        $user = [
            'userIntegration' => [
                'userId' => 1,
                'stripeId' => $cardType,
                'crispId' => 'cus_token'
            ]
        ];

        $order = [
            'id' => 1,
            'amount' => 200,
            'companyId' => 2,
            'providerId' => 1,
            'service' => [
                'id' => 2,
                'name' => 'Pressing, Retouche, Cordonnerie, Blanchisserie en entreprise',
                'categoryId' => 2,
                'className' => 'PressingAtCompany',
                'externalLink' => null,
                'createdAt' => Carbon::now(),
                'updatedAt' => Carbon::now()
            ],
            'status' => 2,
            'userId' => 2
        ];

        Stripe::setCardId($cardType);
        $response = Stripe::pay($order, $user);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function createPaymentWith3ds()
    {
        $cardType = 'tok_threeDSecure2Required';

        $user = [
            'userIntegration' => [
                'userId' => 1,
                'stripeId' => $cardType,
                'crispId' => 'cus_token'
            ]
        ];

        $order = [
            'id' => 1,
            'amount' => 200,
            'companyId' => 2,
            'providerId' => 1,
            'service' => [
                'id' => 2,
                'name' => 'Pressing, Retouche, Cordonnerie, Blanchisserie en entreprise',
                'categoryId' => 2,
                'className' => 'PressingAtCompany',
                'externalLink' => null,
                'createdAt' => Carbon::now(),
                'updatedAt' => Carbon::now()
            ],
            'status' => 2,
            'userId' => 2
        ];

        Stripe::setCardId($cardType);
        $response = Stripe::pay($order, $user);

        $this->assertNotNull($response);
        $this->assertEquals(2, $response['status']);
        $this->assertEquals('Authentication required', $response['message']);
    }

    /**
     * @test
     */
    public function createPayment()
    {
        $cardType = 'tok_visa';

        $user = [
            'userIntegration' => [
                'userId' => 1,
                'stripeId' => $cardType,
                'crispId' => 'cus_token'
            ]
        ];

        $order = [
            'id' => 1,
            'amount' => 200,
            'companyId' => 2,
            'providerId' => 1,
            'service' => [
                'id' => 2,
                'name' => 'Pressing, Retouche, Cordonnerie, Blanchisserie en entreprise',
                'categoryId' => 2,
                'className' => 'PressingAtCompany',
                'externalLink' => null,
                'createdAt' => Carbon::now(),
                'updatedAt' => Carbon::now()
            ],
            'status' => 2,
            'userId' => 2
        ];

        Stripe::setCardId($cardType);
        $response = Stripe::pay($order, $user);

        $this->assertNotNull($response);
        $this->assertEquals(1, $response['status']);
        $this->assertEquals('Payment success', $response['message']);
    }
}
