<?php


namespace App\Services\Pressing;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Pressing
{
    private function checkIfExist(array $providers, string $email, string $password): ?array
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

    public static function getProviderOrder(int $id, string $status): ?array
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->get(env('MIX_OURANOS_PRESSING_ORDER_URL') . 'provider/' . $id . '/order/' . $status);

        if (!$response->successful()) {
            Log::error($response->status() === 404 ? 'Route url not found' : $response->body(), [
                'className' => class_basename(self::class),
                'functionName' => __FUNCTION__,
                'codeStatus' => $response->status(),
            ]);

            return null;
        }

        return $response->json();
    }

    public static function changeStatus(int $orderId, int $statusId): ?JsonResponse
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->put(env('MIX_OURANOS_PRESSING_ORDER_URL') . 'order/' . $orderId, [
                'status' => $statusId
            ]);

        if (!$response->successful()) {
            Log::error($response->status() === 404 ? 'Route url not found' : $response->body(), [
                'className' => class_basename(self::class),
                'functionName' => __FUNCTION__,
                'codeStatus' => $response->status(),
            ]);

            return null;
        }

        return $response->json();
    }

    public static function checkProvider(string $email, ?string $password = null): ?array
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->get(env('MIX_OURANOS_PRESSING_ORDER_URL') . 'provider');

        if (!$response->successful()) {
            Log::error($response->status() === 404 ? 'Route url not found' : $response->body(), [
                'className' => class_basename(self::class),
                'functionName' => __FUNCTION__,
                'codeStatus' => $response->status(),
            ]);

            return null;
        }

        if (!$user = (new Pressing)->checkIfExist($response->json(), $email, $password)) {
            Log::error('Could not log user ' . $email, [
                'className' => class_basename(self::class),
                'functionName' => __FUNCTION__,
                'codeStatus' => $response->status(),
            ]);

            return null;
        }

        return $user;
    }
}
