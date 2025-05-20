<?php

namespace Pogodoc;

use Pogodoc\Templates\TemplatesClient;
use Pogodoc\Documents\DocumentsClient;
use Pogodoc\Tokens\TokensClient;
use GuzzleHttp\ClientInterface;
use Pogodoc\Core\RawClient;

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

        $this->templates = new TemplatesClient($this->client);
        $this->documents = new DocumentsClient($this->client);
        $this->tokens = new TokensClient($this->client);
    }
}
