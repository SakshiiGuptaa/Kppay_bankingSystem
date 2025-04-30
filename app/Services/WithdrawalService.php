<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WithdrawalService
{
    // Function to fetch the access token
    public static function getAccessToken()
    {
        try {
            $username = env('WITHDRAW_USERNAME');
            $password = env('WITHDRAW_PASSWORD');
            $subscriptionKey = env('WITHDRAW_SUBSCRIPTION_KEY');
            $targetEnvironment = env('WITHDRAW_TARGET_ENVIRONMENT');

            $response = Http::withBasicAuth($username, $password)
                ->withHeaders([
                    'Ocp-Apim-Subscription-Key' => $subscriptionKey,
                    'X-Target-Environment' => $targetEnvironment,
                ])
                ->post('https://proxy.momoapi.mtn.com/disbursement/token/');

            if ($response->successful()) {
                return $response->json()['access_token'] ?? null;
            } else {
                \Log::error('Failed to get access token', ['response' => $response->body()]);
                return null;
            }
        } catch (\Exception $e) {
            \Log::error('Error getting access token', ['message' => $e->getMessage()]);
            return null;
        }
    }

    // Function to transfer funds
    public static function transferFunds($amount, $phoneNumber)
    {
        try {
            // Step 1: Fetch the access token
            $accessToken = self::getAccessToken();

            if (!$accessToken) {
                \Log::error('Failed to get access token for fund transfer');
                return ['success' => false, 'message' => 'Authentication failed'];
            }

            // Step 2: Transfer funds
            $subscriptionKey = env('WITHDRAW_SUBSCRIPTION_KEY');
            $targetEnvironment = env('WITHDRAW_TARGET_ENVIRONMENT');
            $referenceId = (string) Str::uuid(); // Generate a unique X-Reference-Id

            $payload = [
                "amount" => $amount,
                "currency" => "EUR",
                "externalId" => "6356636", // Customize if needed
                "payee" => [
                    "partyIdType" => "MSISDN",
                    "partyId" => $phoneNumber,
                ],
                "payerMessage" => "Pay for product",
                "payeeNote" => "payer note",
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Ocp-Apim-Subscription-Key' => $subscriptionKey,
                'X-Target-Environment' => $targetEnvironment,
                'X-Reference-Id' => $referenceId,
            ])->post('https://proxy.momoapi.mtn.com/disbursement/v1_0/transfer', $payload);

            // Check if the status code is in the 2xx range (success)
            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Transfer successful',
                    'referenceId' => $referenceId,
                    'response' => $response->json(),
                ];
            }

            // Handle non-2xx responses (client or server errors)
            $errorCode = $response->status(); // Get the HTTP status code
            $errorMessage = $response->body(); // Get the body content of the error response

            \Log::error('Fund transfer failed', [
                'status_code' => $errorCode,
                'response' => $errorMessage,
            ]);

            return [
                'success' => false,
                'message' => 'Transfer failed',
                'error_code' => $errorCode,
                'error_message' => $errorMessage,
            ];

        } catch (\Exception $e) {
            \Log::error('Error in fund transfer', ['message' => $e->getMessage()]);
            return ['success' => false, 'message' => 'An error occurred'];
        }
    }

     /**
     * Check Transfer Status
     *
     * @param string $referenceId
     * @return array
     */
    public static function checkTransferStatus($referenceId)
    {
        try {
            // Get the token
            $accessToken = self::getAccessToken();

            if (!$accessToken) {
                \Log::error('Failed to get access token for status check');
                return ['success' => false, 'message' => 'Authentication failed'];
            }

            $subscriptionKey = env('WITHDRAW_SUBSCRIPTION_KEY');
            $targetEnvironment = env('WITHDRAW_TARGET_ENVIRONMENT');

            // Request transfer status
            $url = "https://proxy.momoapi.mtn.com/disbursement/v1_0/transfer/$referenceId";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Ocp-Apim-Subscription-Key' => $subscriptionKey,
                'X-Target-Environment' => $targetEnvironment,
            ])->get($url);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            } else {
                \Log::error('Failed to check transfer status', ['response' => $response->body()]);
                return [
                    'success' => false,
                    'message' => 'Failed to check transfer status',
                    'response' => $response->json(),
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Error checking transfer status', ['message' => $e->getMessage()]);
            return ['success' => false, 'message' => 'An error occurred'];
        }
    }

}
