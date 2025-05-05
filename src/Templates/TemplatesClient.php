<?php

namespace Pogodoc\Templates;

use Pogodoc\Core\RawClient;
use Pogodoc\Templates\Types\InitializeTemplateCreationResponse;
use Pogodoc\Exceptions\PogodocException;
use Pogodoc\Exceptions\PogodocApiException;
use Pogodoc\Core\JsonApiRequest;
use Pogodoc\Environments;
use Pogodoc\Core\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Pogodoc\Templates\Requests\SaveCreatedTemplateRequest;
use Pogodoc\Core\JsonDecoder;
use Pogodoc\Templates\Requests\UpdateTemplateRequest;
use Pogodoc\Templates\Types\UpdateTemplateResponse;
use Pogodoc\Templates\Requests\GenerateTemplatePreviewsRequest;
use Pogodoc\Templates\Types\GenerateTemplatePreviewsResponse;
use Pogodoc\Templates\Types\GeneratePresignedGetUrlResponse;
use Pogodoc\Templates\Types\GetTemplateIndexHtmlResponse;
use Pogodoc\Templates\Requests\UploadTemplateIndexHtmlRequest;
use Pogodoc\Templates\Types\CloneTemplateResponse;

class TemplatesClient
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
     * Initializes template creation by generating a unique ID and providing a presigned URL for template ZIP upload. Sets unfinished tag for tracking incomplete templates.
     *
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return InitializeTemplateCreationResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function initializeTemplateCreation(?array $options = null): InitializeTemplateCreationResponse
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/init",
                    method: HttpMethod::GET,
                ),
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return InitializeTemplateCreationResponse::fromJson($json);
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
     * Finalizes template creation by saving template info to Strapi, copying preview files to permanent storage, and creating template index. Removes unfinished tag upon completion.
     *
     * @param string $templateId
     * @param SaveCreatedTemplateRequest $request
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return mixed
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function saveCreatedTemplate(string $templateId, SaveCreatedTemplateRequest $request, ?array $options = null): mixed
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/$templateId",
                    method: HttpMethod::POST,
                    body: $request,
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

    /**
     * Updates template content, handles S3 storage cleanup for old content, updates template metadata in Strapi, and manages preview files. Removes unfinished tags after successful update.
     *
     * @param string $templateId
     * @param UpdateTemplateRequest $request
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return UpdateTemplateResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function updateTemplate(string $templateId, UpdateTemplateRequest $request, ?array $options = null): UpdateTemplateResponse
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/$templateId",
                    method: HttpMethod::PUT,
                    body: $request,
                ),
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return UpdateTemplateResponse::fromJson($json);
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
     * Deletes a template from Strapi and associated S3 storage. Removes all associated files and metadata.
     *
     * @param string $templateId
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return mixed
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function deleteTemplate(string $templateId, ?array $options = null): mixed
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/$templateId",
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

    /**
     * Extracts contents from an uploaded template ZIP file and stores individual files in the appropriate S3 storage structure.
     *
     * @param string $templateId
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return mixed
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function extractTemplateFiles(string $templateId, ?array $options = null): mixed
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/$templateId/unzip",
                    method: HttpMethod::POST,
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

    /**
     * Creates both PNG and PDF preview files for template visualization. Generates previews in parallel and returns URLs for both formats.
     *
     * @param string $templateId
     * @param GenerateTemplatePreviewsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return GenerateTemplatePreviewsResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function generateTemplatePreviews(string $templateId, GenerateTemplatePreviewsRequest $request, ?array $options = null): GenerateTemplatePreviewsResponse
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/$templateId/render-previews",
                    method: HttpMethod::POST,
                    body: $request,
                ),
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return GenerateTemplatePreviewsResponse::fromJson($json);
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
     * Generates a presigned URL for template access. Used for downloading template files from S3 storage.
     *
     * @param string $templateId
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return GeneratePresignedGetUrlResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function generatePresignedGetUrl(string $templateId, ?array $options = null): GeneratePresignedGetUrlResponse
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/$templateId/presigned-url",
                    method: HttpMethod::GET,
                ),
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return GeneratePresignedGetUrlResponse::fromJson($json);
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
     * Retrieves the template index.html file from S3 storage. Used for rendering the template in the browser.
     *
     * @param string $templateId
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return GetTemplateIndexHtmlResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function getTemplateIndexHtml(string $templateId, ?array $options = null): GetTemplateIndexHtmlResponse
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/$templateId/index-html",
                    method: HttpMethod::GET,
                ),
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return GetTemplateIndexHtmlResponse::fromJson($json);
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
     * Uploads the template index.html file to S3 storage. Used for rendering the template in the browser.
     *
     * @param string $templateId
     * @param UploadTemplateIndexHtmlRequest $request
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return mixed
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function uploadTemplateIndexHtml(string $templateId, UploadTemplateIndexHtmlRequest $request, ?array $options = null): mixed
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/$templateId/index-html",
                    method: HttpMethod::POST,
                    body: $request,
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

    /**
     * Creates a new template by duplicating an existing template's content and metadata. Includes copying preview files and template index.
     *
     * @param string $templateId
     * @param ?array{
     *   baseUrl?: string,
     * } $options
     * @return CloneTemplateResponse
     * @throws PogodocException
     * @throws PogodocApiException
     */
    public function cloneTemplate(string $templateId, ?array $options = null): CloneTemplateResponse
    {
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $this->options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::Default_->value,
                    path: "templates/$templateId/clone",
                    method: HttpMethod::POST,
                ),
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                return CloneTemplateResponse::fromJson($json);
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
