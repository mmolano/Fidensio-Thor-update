<?php

namespace App\Services\Stripe;

use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;

class StripeFake
{
    private Generator $faker;
    private Carbon $carbonExpire;
    private array $createUserHistory = [];
    private bool $defaultCardSet = false;
    private array $cards = [
        'tok_visa' => 4242,
        'tok_mastercard' => 4444,
        'tok_amex' => 8431,
        'tok_chargeCustomerFail' => 9995,
        'tok_threeDSecure2Required' => 3220
    ];
    private array $createCardHistory = [];
    private array $defaultCard;


    public function __construct()
    {
        $this->carbonExpire = Carbon::now()->addYear();
        $this->faker = Factory::create('fr_FR');
    }

    public function createUser(array $user, array $userData): ?string
    {
        if (!empty($this->createUserHistory[$user['email']])) {
            return $this->createUserHistory[$user['email']];
        }
        $this->createUserHistory[$user['email']] = 'test_cus_' . $this->faker->password;

        return $this->createUserHistory[$user['email']];
    }

    public function newCard(array $userIntegration, string $stripeCardToken): ?array
    {
        if (empty($this->cards[$stripeCardToken])) {
            return null;
        }
        $card = [
            'id' => $stripeCardToken,
            'name' => $stripeCardToken,
            'default' => !$this->defaultCardSet,
            'last4' => $this->cards[$stripeCardToken],
            'expireMonth' => $this->carbonExpire->format('m'),
            'expireYear' => $this->carbonExpire->format('Y'),
        ];
        if (!$this->defaultCardSet) {
            $this->defaultCardSet = true;
        }
        array_push($this->createCardHistory, $card);

        return $card;
    }

    public function getDefaultCard(array $userIntegration): ?array
    {
        if (empty($this->createCardHistory)) {
            return null;
        }

        foreach ($this->createCardHistory as $card) {
            if ($card['default']) {
                return $card;
            }
        }

        return null;
    }

    public function pay(array $order, array $user): ?array
    {
        if (!$card = $this->getDefaultCard($user['integration'])) {
            return null;
        }

        switch ($card['id']) {
            case 'tok_chargeCustomerFail':
                return null;
            case 'tok_threeDSecure2Required':
                return [
                    'status' => 2,
                    'message' => 'Authentication required',
                    'intentId' => 'intent_threeDSecure2Required',
                    'paymentToken' => 'tok_threeDSecure2Required'
                ];
            default:
                return [
                    'status' => 1,
                    'message' => 'Payment success',
                    'intentId' => 'intent_threeDSecure2Required'
                ];
        }
    }
}
