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

    private function checkUserAlreadyExist(string $email): ?string
    {
        try {
            $userStripe = $this->client->customers->all([
                'email' => $email
            ]);
        } catch (ApiErrorException $exception) {
            Log::error('Error exception', [
                'className' => class_basename(self::class),
                'functionName' => __FUNCTION__,
                'message' => $exception->getJsonBody()
            ]);

            return null;
        }

        if (empty($userStripe->data[0])) {
            return null;
        }
        return $userStripe->data[0]->id;
    }

    public function createUser(array $user, array $userData): ?string
    {
        if ($stripeId = $this->checkUserAlreadyExist($user['email'])) {
            return $stripeId;
        }
        try {
            $userStripe = $this->client->customers->create([
                'email' => $user['email'],
                'name' => $userData['firstName'] . ' ' . $userData['lastName']
            ]);
        } catch (ApiErrorException $exception) {
            Log::error('Error exception', [
                'className' => class_basename(self::class),
                'functionName' => __FUNCTION__,
                'message' => $exception->getJsonBody()
            ]);

            return null;
        }

        return $userStripe->id;
    }

    public function newCard(array $userIntegration, string $stripeCardToken): ?array
    {
        try {
            $card = $this->client->customers->createSource($userIntegration['stripeId'], [
                'source' => $stripeCardToken
            ]);
        } catch (ApiErrorException $exception) {
            Log::error('Error exception', [
                'className' => class_basename(self::class),
                'functionName' => __FUNCTION__,
                'message' => $exception->getJsonBody()
            ]);

            return null;
        }

        try {
            $userStripe = $this->client->customers->retrieve($userIntegration['stripeId']);
        } catch (ApiErrorException $exception) {
            Log::error('Error exception', [
                'className' => class_basename(self::class),
                'functionName' => __FUNCTION__,
                'message' => $exception->getJsonBody()
            ]);

            return null;
        }

        return [
            'id' => $card->id,
            'name' => $card->name,
            'default' => $card->id === $userStripe->default_source,
            'last4' => $card->last4,
            'expireMonth' => $card->exp_month,
            'expireYear' => $card->exp_year
        ];
    }

    public function getDefaultCard(array $userIntegration): ?array
    {
        try {
            $userStripe = $this->client->customers->retrieve($userIntegration['stripeId']);
            $card = $this->client->customers->retrieveSource($userIntegration['stripeId'], $userStripe->default_source);
        } catch (\Exception $exception) {
            Log::error('Error exception', [
                'className' => class_basename(self::class),
                'functionName' => __FUNCTION__,
                'message' => $exception->getMessage()
            ]);

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
