<?php


namespace App\Services\Pressing;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Pressing
{
    public static function getProviderOrder(int $id, string $status): ?array
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->get(env('MIX_OURANOS_PRESSING_ORDER_URL') . $id . '/order/' . $status);

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

    public static function changeStatus(int $orderId, int $providerId, string $statusId): ?JsonResponse
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->post(env('MIX_OURANOS_PRESSING_ORDER_URL') . $providerId . '/order/status', [
                'orderId' => $orderId,
                'statusId' => $statusId
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
}
