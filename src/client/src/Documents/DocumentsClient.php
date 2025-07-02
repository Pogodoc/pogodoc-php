<?php

namespace Pogodoc\Documents;

use GuzzleHttp\ClientInterface;
use Pogodoc\Core\Client\RawClient;
use Pogodoc\Documents\Requests\InitializeRenderJobRequest;
use Pogodoc\Documents\Types\InitializeRenderJobResponse;
use Pogodoc\Exceptions\PogodocException;
use Pogodoc\Exceptions\PogodocApiException;
use Pogodoc\Core\Json\JsonApiRequest;
use Pogodoc\Environments;
use Pogodoc\Core\Client\HttpMethod;
use JsonException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Client\ClientExceptionInterface;
use Pogodoc\Documents\Requests\StartRenderJobRequest;
use Pogodoc\Documents\Types\StartRenderJobResponse;
use Pogodoc\Documents\Requests\StartImmediateRenderRequest;
use Pogodoc\Documents\Types\StartImmediateRenderResponse;
use Pogodoc\Documents\Types\GetJobStatusResponse;

class DocumentsClient
{
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
     * @param RawClient $client
     * @param ?array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options
     */
    public function __construct(
        RawClient $client,
        ?array $options = null,
    ) {
        $this->client = $client;
        $this->options = $options ?? [];
    }

    /**
     * Creates a new render job with a unique ID, sets up S3 storage for template and data files, and generates presigned upload URLs if needed. Requires subscription check.
     *
     * @param InitializeRenderJobRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return InitializeRenderJobResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function initializeRenderJob(InitializeRenderJobRequest $request, ?array $options = null): InitializeRenderJobResponse
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "documents/init",
                    method: HttpMethod::POST,
                    body: $request,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return InitializeRenderJobResponse::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new PogodocException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
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

    /**
     * Takes a previously initialized job, updates its status to in-progress, and triggers the rendering process using Puppeteer. Can optionally wait for render completion.
     *
     * @param string $jobId
     * @param StartRenderJobRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return StartRenderJobResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function startRenderJob(string $jobId, StartRenderJobRequest $request = new StartRenderJobRequest(), ?array $options = null): StartRenderJobResponse
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "documents/{$jobId}/render",
                    method: HttpMethod::POST,
                    body: $request,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return StartRenderJobResponse::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new PogodocException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
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

    /**
     * Combines initialization and rendering in one step. Creates a job, uploads template/data directly, starts rendering, and adds the document to Strapi. Requires subscription check.
     *
     * @param StartImmediateRenderRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return StartImmediateRenderResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function startImmediateRender(StartImmediateRenderRequest $request, ?array $options = null): StartImmediateRenderResponse
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "documents/immediate-render",
                    method: HttpMethod::POST,
                    body: $request,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return StartImmediateRenderResponse::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new PogodocException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
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

    /**
     * Fetches detailed job information from S3 storage including job status, template ID, target format, and output details if available.
     *
     * @param string $jobId ID of the render job
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return GetJobStatusResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function getJobStatus(string $jobId, ?array $options = null): GetJobStatusResponse
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "jobs/{$jobId}",
                    method: HttpMethod::GET,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return GetJobStatusResponse::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new PogodocException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
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
