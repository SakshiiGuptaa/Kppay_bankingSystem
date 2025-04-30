<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class MomoPaymentHelper
{
    protected static function getEnvConfig()
    {
        return [
            'username' => env('MOMO_USERNAME'),
            'password' => env('MOMO_PASSWORD'),
            'subscriptionKey' => env('MOMO_SUBSCRIPTION_KEY'),
            'targetEnvironment' => env('MOMO_TARGET_ENVIRONMENT'),
        ];
    }

    public static function generateUUIDv4()
    {
        return (string) Str::uuid();
    }

    public static function getAccessToken ()
    {
        $config = self::getEnvConfig();

        $url = "https://proxy.momoapi.mtn.com/collection/token/";

        $response = Http::withBasicAuth($config['username'], $config['password'])
            ->withHeaders([
                'Ocp-Apim-Subscription-Key' => $config['subscriptionKey'],
                'X-Target-Environment' => $config['targetEnvironment'],
            ])
            ->post($url);

        if ($response->successful() && isset($response['access_token'])) {
            return $response['access_token'];
        }

        return null;
    }

    public static function initiatePayment($amount, $msisdn)
    {
        $config = self::getEnvConfig();
        $url = "https://proxy.momoapi.mtn.com/collection/v1_0/requesttopay";
        $referenceId = self::generateUUIDv4();

        $token = self::getAccessToken();

        if (!$token) {
            return [
                'status' => 'error',
                'message' => 'Unable to retrieve access token',
            ];
        }

        $payload = [
            'amount' => $amount,
            'currency' => 'XOF',
            'externalId' => '1234567890',
            'payer' => [
                'partyIdType' => 'MSISDN',
                'partyId' => $msisdn,
            ],
            'payerMessage' => 'Test Payment',
            'payeeNote' => 'Test Payment',
        ];

        $response = Http::withToken($token)
            ->withHeaders([
                'Ocp-Apim-Subscription-Key' => $config['subscriptionKey'],
                'X-Target-Environment' => $config['targetEnvironment'],
                'X-Reference-Id' => $referenceId,
            ])
            ->post($url, $payload);

            if ($response->successful()) {
                // Call checkPayment with the generated referenceId
                // $checkResponse = self::checkPayment($referenceId);
                return [
                    'response_code' => $response->status(),
                    'response_body' => $response->json(),
                    'uuid' => $referenceId,
                    // 'check_response' => $checkResponse,
                ];
            }            
    }
    
     public static function getTransactionStatus($refId)
    {
        $config = self::getEnvConfig();
        $url = "https://proxy.momoapi.mtn.com/collection/v1_0/requesttopay/{$refId}";

        $token = self::getAccessToken();

        if (!$token) {
            return [
                'status' => 'error',
                'message' => 'Unable to retrieve access token',
            ];
        }

        $response = Http::withToken($token)
            ->withHeaders([
                'Ocp-Apim-Subscription-Key' => $config['subscriptionKey'],
                'X-Target-Environment' => $config['targetEnvironment'],
            ])
            ->get($url);

        if (!$response->successful()) {
            return [
                'status' => 'error',
                'message' => 'Failed to fetch transaction status',
            ];
        }
    
        // $responseData = $response->json();
        // return self::processTransactionStatus($responseData);
        
        return $response->json(); // Return the entire response data
    }

    protected static function processTransactionStatus($responseData)
    {
        if (isset($responseData['status'])) {
            switch ($responseData['status']) {
                case 'PENDING':
                    return '0'; // Pending status
                case 'SUCCESS':
                    return '1'; // Successful transaction
                case 'FAILED':
                    return '2'; // Failed transaction
                default:
                    return '3'; // Unknown status
            }
        }

        return 'Status not found in the response.';
    }


    // public static function checkPayment($referenceId)
    // {
    //     $config = self::getEnvConfig();
    //     $url = "https://proxy.momoapi.mtn.com/collection/v1_0/requesttopay/$referenceId";
    
    //     $token = self::getAccessToken();
    
    //     if (!$token) {
    //         return [
    //             'status' => 'error',
    //             'message' => 'Unable to retrieve access token',
    //         ];
    //     }
    
    //     $response = Http::withToken($token)
    //         ->withHeaders([
    //             'Ocp-Apim-Subscription-Key' => $config['subscriptionKey'],
    //             'X-Target-Environment' => $config['targetEnvironment'],
    //         ])
    //         ->get($url);
    
    //     // Parse the response
    //     $responseBody = $response->json();
    
    //     if ($response->status() === 200) {
    //         $status = $responseBody['status'] ?? 'unknown';
    //         return [
    //             'status' => $status, // e.g., SUCCESSFUL, FAILED, PENDING
    //             'reason' => $responseBody['reason'] ?? null, // Reason for failure if any
    //             'message' => $responseBody['payerMessage'] ?? null, // Optional additional message
    //         ];
    //     } else {
    //         return [
    //             'status' => 'error',
    //             'message' => $responseBody['message'] ?? 'Unable to retrieve payment status',
    //         ];
    //     }
    // }
    
}
