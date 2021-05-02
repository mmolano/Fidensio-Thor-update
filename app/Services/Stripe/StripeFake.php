<?php

namespace App\Services\Stripe;

use Carbon\Carbon;

class StripeFake
{
    private Carbon $carbonExpire;
    private array $cardsLast4 = [
        'tok_visa' => 4242,
        'tok_mastercard' => 4444,
        'tok_amex' => 8431,
        'tok_chargeCustomerFail' => 9995,
        'tok_threeDSecure2Required' => 3220
    ];
    private array $defaultCard;

    public function __construct()
    {
        $this->carbonExpire = Carbon::now()->addYear();
    }

    public function setCardId(string $card)
    {
        if (empty($this->cardsLast4[$card])) {
            return null;
        }

        $this->defaultCard = [
            'id' => $card,
            'name' => $card,
            'default' => true,
            'last4' => $this->cardsLast4[$card],
            'expireMonth' => $this->carbonExpire->format('m'),
            'expireYear' => $this->carbonExpire->format('Y'),
        ];
    }

    public function getDefaultCard(array $user): ?array
    {
        if (empty($this->cardsLast4[$user['stripeId']])) {
            return null;
        }

        return $this->defaultCard;
    }

    public function pay(array $order, array $user): ?array
    {
        if (!$card = $this->getDefaultCard($user['userIntegration'])) {
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
