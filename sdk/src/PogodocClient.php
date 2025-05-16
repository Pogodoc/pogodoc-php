<?php

namespace Pogodoc;

use Pogodoc\Templates\TemplatesClient;
use Pogodoc\Documents\DocumentsClient;
use Pogodoc\Tokens\TokensClient;
use GuzzleHttp\ClientInterface;
use Pogodoc\Core\Client\RawClient;
use Pogodoc\Exceptions\PogodocException;
use Pogodoc\Exceptions\PogodocApiException;
use Pogodoc\Core\Json\JsonApiRequest;
use Pogodoc\Core\Client\HttpMethod;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Client\ClientExceptionInterface;

class PogodocClient
{
    /**
     * @var TemplatesClient $templates
     */
    public TemplatesClient $templates;

    /**
     * @var DocumentsClient $documents
     */
    public DocumentsClient $documents;

    /**
     * @var TokensClient $tokens
     */
    public TokensClient $tokens;

    /**
     * @var array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options
     */
    private array $options;

    /**
     * @var RawClient $client
     */
    private RawClient $client;

    /**
     * @param ?string $token The token to use for authentication.
     * @param ?array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options
     */
    public function __construct(
        ?string $token = null,
        ?array $options = null,
    ) {
        $defaultHeaders = [
            'X-Fern-Language' => 'PHP',
            'X-Fern-SDK-Name' => 'Pogodoc',
        ];
        if ($token != null) {
            $defaultHeaders['Authorization'] = "Bearer $token";
        }

        $this->options = $options ?? [];
        $this->options['headers'] = array_merge(
            $defaultHeaders,
            $this->options['headers'] ?? [],
        );

        $this->client = new RawClient(
            options: $this->options,
        );

        $this->templates = new TemplatesClient($this->client, $this->options);
        $this->documents = new DocumentsClient($this->client, $this->options);
        $this->tokens = new TokensClient($this->client, $this->options);
    }

    /**
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function postBoshe(?array $options = null): void
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "boshe",
                    method: HttpMethod::POST,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                return;
            }
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response === null) {
                throw new PogodocException(message: $e->getMessage(), previous: $e);
            }
            throw new PogodocApiException(
                message: "API request failed",
                statusCode: $response->getStatusCode(),
                body: $response->getBody()->getContents(),
            );
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
