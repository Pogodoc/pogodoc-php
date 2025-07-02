<?php

namespace Pogodoc\Templates;

use GuzzleHttp\ClientInterface;
use Pogodoc\Core\Client\RawClient;
use Pogodoc\Templates\Types\InitializeTemplateCreationResponse;
use Pogodoc\Exceptions\PogodocException;
use Pogodoc\Exceptions\PogodocApiException;
use Pogodoc\Core\Json\JsonApiRequest;
use Pogodoc\Environments;
use Pogodoc\Core\Client\HttpMethod;
use JsonException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Client\ClientExceptionInterface;
use Pogodoc\Templates\Requests\SaveCreatedTemplateRequest;
use Pogodoc\Templates\Requests\UpdateTemplateRequest;
use Pogodoc\Templates\Types\UpdateTemplateResponse;
use Pogodoc\Templates\Types\DeleteTemplateResponse;
use Pogodoc\Templates\Requests\GenerateTemplatePreviewsRequest;
use Pogodoc\Templates\Types\GenerateTemplatePreviewsResponse;
use Pogodoc\Templates\Types\GeneratePresignedGetUrlResponse;
use Pogodoc\Templates\Types\GetTemplateIndexHtmlResponse;
use Pogodoc\Templates\Requests\UploadTemplateIndexHtmlRequest;
use Pogodoc\Templates\Types\CloneTemplateResponse;

class TemplatesClient
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
     * Initializes template creation by generating a unique ID and providing a presigned URL for template ZIP upload. Sets unfinished tag for tracking incomplete templates.
     *
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return InitializeTemplateCreationResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function initializeTemplateCreation(?array $options = null): InitializeTemplateCreationResponse
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/init",
                    method: HttpMethod::GET,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return InitializeTemplateCreationResponse::fromJson($json);
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
     * Finalizes template creation by saving template info to Strapi, copying preview files to permanent storage, and creating template index. Removes unfinished tag upon completion.
     *
     * @param string $templateId
     * @param SaveCreatedTemplateRequest $request
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
    public function saveCreatedTemplate(string $templateId, SaveCreatedTemplateRequest $request, ?array $options = null): void
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/{$templateId}",
                    method: HttpMethod::POST,
                    body: $request,
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

    /**
     * Updates template content, handles S3 storage cleanup for old content, updates template metadata in Strapi, and manages preview files. Removes unfinished tags after successful update.
     *
     * @param string $templateId
     * @param UpdateTemplateRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return UpdateTemplateResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function updateTemplate(string $templateId, UpdateTemplateRequest $request, ?array $options = null): UpdateTemplateResponse
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/{$templateId}",
                    method: HttpMethod::PUT,
                    body: $request,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return UpdateTemplateResponse::fromJson($json);
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
     * Deletes a template from Strapi and associated S3 storage. Removes all associated files and metadata.
     *
     * @param string $templateId ID of the template to be deleted
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return DeleteTemplateResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function deleteTemplate(string $templateId, ?array $options = null): DeleteTemplateResponse
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/{$templateId}",
                    method: HttpMethod::DELETE,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return DeleteTemplateResponse::fromJson($json);
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
     * Extracts contents from an uploaded template ZIP file and stores individual files in the appropriate S3 storage structure.
     *
     * @param string $templateId ID of the template to be used
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
    public function extractTemplateFiles(string $templateId, ?array $options = null): void
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/{$templateId}/unzip",
                    method: HttpMethod::PATCH,
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

    /**
     * Creates both PNG and PDF preview files for template visualization. Generates previews in parallel and returns URLs for both formats.
     *
     * @param string $templateId ID of the template to be used
     * @param GenerateTemplatePreviewsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return GenerateTemplatePreviewsResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function generateTemplatePreviews(string $templateId, GenerateTemplatePreviewsRequest $request, ?array $options = null): GenerateTemplatePreviewsResponse
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/{$templateId}/render-previews",
                    method: HttpMethod::POST,
                    body: $request,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return GenerateTemplatePreviewsResponse::fromJson($json);
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
     * Generates a presigned URL for template access. Used for downloading template files from S3 storage.
     *
     * @param string $templateId ID of the template that is being downloaded
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return GeneratePresignedGetUrlResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function generatePresignedGetUrl(string $templateId, ?array $options = null): GeneratePresignedGetUrlResponse
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/{$templateId}/presigned-url",
                    method: HttpMethod::GET,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return GeneratePresignedGetUrlResponse::fromJson($json);
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
     * Retrieves the template index.html file from S3 storage. Used for rendering the template in the browser.
     *
     * @param string $templateId ID of the template to be used
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return GetTemplateIndexHtmlResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function getTemplateIndexHtml(string $templateId, ?array $options = null): GetTemplateIndexHtmlResponse
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/{$templateId}/index-html",
                    method: HttpMethod::GET,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return GetTemplateIndexHtmlResponse::fromJson($json);
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
     * Uploads the template index.html file to S3 storage. Used for rendering the template in the browser.
     *
     * @param string $templateId ID of the template to be used
     * @param UploadTemplateIndexHtmlRequest $request
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
    public function uploadTemplateIndexHtml(string $templateId, UploadTemplateIndexHtmlRequest $request, ?array $options = null): void
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/{$templateId}/index-html",
                    method: HttpMethod::POST,
                    body: $request,
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

    /**
     * Creates a new template by duplicating an existing template's content and metadata. Includes copying preview files and template index.
     *
     * @param string $templateId ID of the template to be used
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return CloneTemplateResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function cloneTemplate(string $templateId, ?array $options = null): CloneTemplateResponse
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/{$templateId}/clone",
                    method: HttpMethod::POST,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return CloneTemplateResponse::fromJson($json);
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
