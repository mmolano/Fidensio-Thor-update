<?php


namespace App\Services\Stripe;

use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class Stripe
{
    private StripeClient $client;

    public function __construct()
    {
        $this->client = new StripeClient(env('STRIPE_SECRET'));
    }

    public function getDefaultCard(array $user): ?array
    {
        try {
            $userStripe = $this->client->customers->retrieve($user['stripeId']);
            $card = $this->client->customers->retrieveSource($user['stripeId'], $userStripe->default_source);
        } catch (\Exception $exception) {
            return null;
        }

        return [
            'id' => $card->id,
            'name' => $card->name,
            'default' => true,
            'last4' => $card->last4,
            'expireMonth' => $card->exp_month,
            'expireYear' => $card->exp_year
        ];
    }

    public function pay(array $order, array $user): ?array
    {
        if (!$card = $this->getDefaultCard($user['integration'])) {
            return null;
        }

        try {
            $payment = $this->client->paymentIntents->create([
                'payment_method_types' => ['card'],
                'amount' => $order['amount'],
                'currency' => 'eur',
                'customer' => $user['integration']['stripeId'],
                'metadata' => [
                    'orderId' => $order['id'],
                ],
                'payment_method' => $card['id'],
                'off_session' => 'one_off',
                'confirm' => true

            ]);
        } catch (ApiErrorException $exception) {
            if ($exception->getStripeCode() === 'authentication_required') {
                return [
                    'status' => 2,
                    'message' => 'Authentication required',
                    'intentId' => $exception->getJsonBody()['error']['payment_intent']['id'],
                    'paymentToken' => $exception->getJsonBody()['error']['payment_intent']['client_secret']
                ];
            }

            Log::error('Error exception', [
                'className' => class_basename(self::class),
                'functionName' => __FUNCTION__,
                'message' => $exception->getJsonBody()
            ]);

            return null;
        }

        if ($payment->status != 'succeeded') {
            Log::error('The pay function is passed but the payment is not confirming', [
                'className' => class_basename(self::class),
                'functionName' => __FUNCTION__,
            ]);

            return null;
        }

        return [
            'status' => 1,
            'message' => 'Payment success',
            'intentId' => $payment->id
        ];
    }
}
