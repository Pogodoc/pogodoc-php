<?php

namespace Pogodoc\Tokens;

use Pogodoc\Core\RawClient;
use Pogodoc\Exceptions\PogodocException;
use Pogodoc\Exceptions\PogodocApiException;
use Pogodoc\Core\JsonApiRequest;
use Pogodoc\Environments;
use Pogodoc\Core\HttpMethod;
use Pogodoc\Core\JsonDecoder;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;

class TokensClient
{
    /**
     * @var RawClient $client
     */
    private RawClient $client;

    /**
     * @param RawClient $client
     */
    public function __construct(
        RawClient $client,
    ) {
        $this->client = $client;
    }

    /**
     * Invalidates an API token by storing it in the deleted tokens S3 bucket, preventing future use of the token for authentication.
     *
     * @param string $tokenId
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return mixed
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function deleteApiToken(string $tokenId, ?array $options = null): mixed
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "api-tokens/$tokenId",
                    method: HttpMethod::DELETE,
                ),
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return JsonDecoder::decodeMixed($json);
            }
        } catch (JsonException $e) {
            throw new PogodocException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (ClientExceptionInterface $e) {
            throw new PogodocException(message: $e->getMessage(), previous: $e);
        }
        throw new PogodocApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }
}
