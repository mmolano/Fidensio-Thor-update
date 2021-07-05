<?php


namespace App\Services\Pressing;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Pressing
{
    private static function sendLogs(Response $response, string $function): void
    {
        if (!$response->successful()) {
            Log::error($response->status() === 404 ? 'Route url not found' : $response->body(), [
                'className' => class_basename(self::class),
                'functionName' => $function,
                'codeStatus' => $response->status(),
            ]);
        }
    }

    private static function checkIfExist(array $providers, string $email, string $password): ?array
    {
        foreach ($providers as $provider) {
            if ($provider['email'] === $email && $provider['password'] === $password) {
                return [
                    'id' => $provider['id'],
                    'name' => $provider['name'],
                    'email' => $provider['email']
                ];
            }
        }

        return null;
    }

    public static function checkProvider(string $email, ?string $password = null): ?array
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->get(env('MIX_OURANOS_PRESSING_ORDER_URL') . 'provider');

        self::sendLogs($response, __FUNCTION__);

        if (!$user = self::checkIfExist($response->json(), $email, $password)) {
            Log::error('Could not log user ' . $email, [
                'className' => class_basename(self::class),
                'functionName' => __FUNCTION__,
            ]);

            return null;
        }

        return $user;
    }

    public static function getProviderOrder(int $id, string $status, ?int $page = 1, bool $finished = false): ?array
    {
        $paginate = $finished ? '&paginate=9&page=' . $page : '&paginate=9999';

        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->get(env('MIX_OURANOS_PRESSING_ORDER_URL') . 'provider/' . $id . '/order' . '?status=' . $status . $paginate);

        self::sendLogs($response, __FUNCTION__);

        return $response->json();
    }

    public static function getOrder(int $orderId): ?array
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->get(env('MIX_OURANOS_PRESSING_ORDER_URL') . 'order/' . $orderId);

        self::sendLogs($response, __FUNCTION__);

        return $response->json();
    }

    public static function getUser(int $userId): ?array
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->get(env('MIX_OURANOS_PRESSING_ORDER_URL') . 'user/' . $userId);

        self::sendLogs($response, __FUNCTION__);

        return $response->json();
    }

    public static function getProviderProduct(int $userId): ?array
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->get(env('MIX_OURANOS_PRESSING_ORDER_URL') . 'service/pressingAtCompany', [
                'userId' => $userId
            ]);

        self::sendLogs($response, __FUNCTION__);

        return $response->json();
    }

    public static function postDetail(int $orderId, array $data): ?array
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->post(env('MIX_OURANOS_PRESSING_ORDER_URL') . 'order/' . $orderId . '/detail', $data);

        self::sendLogs($response, __FUNCTION__);

        return $response->json();
    }

    public static function changeStatus(int $orderId, int $statusId): ?array
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->put(env('MIX_OURANOS_PRESSING_ORDER_URL') . 'order/' . $orderId, [
                'status' => $statusId
            ]);

        self::sendLogs($response, __FUNCTION__);

        return $response->json();
    }

    public static function pay(int $orderId): ?array
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->post(env('MIX_OURANOS_PRESSING_ORDER_URL') . 'order/' . $orderId . '/payment');

        self::sendLogs($response, __FUNCTION__);

        if ($response->status() !== 200) {
            return null;
        }

        return $response->json();
    }

    public static function updateLocker(int $companyId, array $data): ?array
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->put(env('MIX_OURANOS_PRESSING_ORDER_URL') . 'company/' . $companyId . '/oldLocker', [
                'orderId' => $data['orderId'],
                'code' => $data['lockerCode'],
                'number' => $data['number'],
            ]);

        self::sendLogs($response, __FUNCTION__);

        return $response->json();
    }

    public static function updateOrderAttributes(int $orderId, array $data): ?array
    {
        $response = Http::withToken(env('MIX_OURANOS_KEY'))
            ->put(env('MIX_OURANOS_PRESSING_ORDER_URL') . 'service/pressingAtCompany/' . $orderId . '/attribute', [
                'providerOrderNumber' => $data['providerOrderNumber'],
                'providerComment' => $data['providerComment'],
            ]);

        self::sendLogs($response, __FUNCTION__);

        return $response->json();
    }
}
