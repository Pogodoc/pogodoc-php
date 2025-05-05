<?php

namespace Pogodoc\Render;

use Pogodoc\Core\RawClient;
use Pogodoc\Render\Requests\InitializeRenderJobRequest;
use Pogodoc\Render\Types\InitializeRenderJobResponse;
use Pogodoc\Exceptions\PogodocException;
use Pogodoc\Exceptions\PogodocApiException;
use Pogodoc\Core\JsonApiRequest;
use Pogodoc\Environments;
use Pogodoc\Core\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Pogodoc\Render\Requests\StartRenderJobRequest;
use Pogodoc\Render\Types\StartRenderJobResponse;
use Pogodoc\Render\Requests\GenerateDocumentPreviewRequest;
use Pogodoc\Render\Types\GenerateDocumentPreviewResponse;
use Pogodoc\Render\Requests\StartImmediateRenderRequest;
use Pogodoc\Render\Types\StartImmediateRenderResponse;
use Pogodoc\Render\Types\GetJobStatusResponse;

class RenderClient
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
     * Creates a new render job with a unique ID, sets up S3 storage for template and data files, and generates presigned upload URLs if needed. Requires subscription check.
     *
     * @param InitializeRenderJobRequest $request
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return InitializeRenderJobResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function initializeRenderJob(InitializeRenderJobRequest $request, ?array $options = null): InitializeRenderJobResponse
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "documents/init",
                    method: HttpMethod::POST,
                    body: $request,
                ),
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return InitializeRenderJobResponse::fromJson($json);
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

    /**
     * Takes a previously initialized job, updates its status to in-progress, and triggers the rendering process using Puppeteer. Can optionally wait for render completion.
     *
     * @param string $jobId
     * @param StartRenderJobRequest $request
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return StartRenderJobResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function startRenderJob(string $jobId, StartRenderJobRequest $request, ?array $options = null): StartRenderJobResponse
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "documents/$jobId/render",
                    method: HttpMethod::POST,
                    body: $request,
                ),
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return StartRenderJobResponse::fromJson($json);
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

    /**
     * Generates a preview by creating a single-page render job, processing it immediately, and returning the output URL. Used for template visualization.
     *
     * @param GenerateDocumentPreviewRequest $request
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return GenerateDocumentPreviewResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function generateDocumentPreview(GenerateDocumentPreviewRequest $request, ?array $options = null): GenerateDocumentPreviewResponse
    {
        $query = [];
        $query['templateId'] = $request->templateId;
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "documents/render-preview",
                    method: HttpMethod::POST,
                    query: $query,
                    body: $request,
                ),
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return GenerateDocumentPreviewResponse::fromJson($json);
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

    /**
     * Combines initialization and rendering in one step. Creates a job, uploads template/data directly, starts rendering, and adds the document to Strapi. Requires subscription check.
     *
     * @param StartImmediateRenderRequest $request
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return StartImmediateRenderResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function startImmediateRender(StartImmediateRenderRequest $request, ?array $options = null): StartImmediateRenderResponse
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "documents/immediate-render",
                    method: HttpMethod::POST,
                    body: $request,
                ),
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return StartImmediateRenderResponse::fromJson($json);
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

    /**
     * Fetches detailed job information from S3 storage including job status, template ID, target format, and output details if available.
     *
     * @param string $jobId
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return GetJobStatusResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function getJobStatus(string $jobId, ?array $options = null): GetJobStatusResponse
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "jobs/$jobId",
                    method: HttpMethod::GET,
                ),
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return GetJobStatusResponse::fromJson($json);
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
