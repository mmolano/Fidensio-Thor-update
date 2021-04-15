<?php


namespace App\Services\Pressing;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Pressing
{
    public static function getProviderOrder(int $id): ?array
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->get(env('MIX_OURANOS_PRESSING_ORDER_URL') . $id . '/order');

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
