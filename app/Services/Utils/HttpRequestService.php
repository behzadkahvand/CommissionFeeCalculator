<?php

namespace App\Services\Utils;

use App\DTO\HttpResultData;

class HttpRequestService
{
    public function __construct(private string $url)
    {
    }

    public function sendGetRequest(array $headers = []): HttpResultData
    {
        return $this->sendRequest($headers);
    }

    public function sendPostRequest(array $postData, array $headers = []): HttpResultData
    {
        return $this->sendRequest($postData, $headers);
    }

    protected function sendRequest(array $headers = [], array $postData = []): HttpResultData
    {
        try {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $this->url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            if ($headers) {
                curl_setopt($curl, CURLOPT_HEADER, $headers);
            }

            if ($postData) {
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
            }

            $data = curl_exec($curl);

            curl_close($curl);

            return new HttpResultData(true, json_decode($data, true));
        } catch (\Exception $exception) {
            return new HttpResultData(false, ["error_message" => $exception->getMessage()]);
        }
    }
}
