<?php

namespace PogodocSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class PogodocUtils 
{
    public static function uploadToS3WithUrl(string $presignedUrl, $stream, int $length, ?string $contentType = null): ResponseInterface
    {
        $client = new Client();

        $headers = [
            'Content-Length' => $length,
        ];

        if ($contentType) {
            $headers['Content-Type'] = $contentType;
        }

        try {
            $response = $client->put($presignedUrl, [
                'headers' => $headers,
                'body'    => $stream,
            ]);

            return $response;
        } catch (RequestException $e) {
            error_log("Error uploading file to S3: " . $e->getMessage());
            throw new \RuntimeException("Error uploading file to S3");
        }
    }

}