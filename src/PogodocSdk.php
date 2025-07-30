<?php

namespace PogodocSdk;

use Pogodoc\PogodocClient;
use Pogodoc\Templates\Requests\GenerateTemplatePreviewsRequest;
use Pogodoc\Templates\Requests\SaveCreatedTemplateRequest;
use Pogodoc\Templates\Types\SaveCreatedTemplateRequestTemplateInfo;
use Pogodoc\Templates\Types\SaveCreatedTemplateRequestPreviewIds;
use Pogodoc\Templates\Requests\UpdateTemplateRequest;
use Pogodoc\Templates\Types\UpdateTemplateRequestTemplateInfo;
use Pogodoc\Templates\Types\UpdateTemplateRequestPreviewIds;
use Pogodoc\Documents\Requests\StartRenderJobRequest;
use Pogodoc\Documents\Requests\InitializeRenderJobRequest;
use Pogodoc\Documents\Types\InitializeRenderJobRequestFormatOpts;
use Pogodoc\Templates\Types\UpdateTemplateRequestTemplateInfoType;
use Pogodoc\Templates\Types\GenerateTemplatePreviewsRequestType;
use Pogodoc\Templates\Types\SaveCreatedTemplateRequestTemplateInfoType;
use Pogodoc\Documents\Types\InitializeRenderJobRequestType;
use Pogodoc\Documents\Types\InitializeRenderJobRequestTarget;
use Pogodoc\Documents\Types\GetJobStatusResponse;
use Pogodoc\Documents\Requests\StartImmediateRenderRequest;
use Pogodoc\Documents\Types\StartImmediateRenderRequestType;
use Pogodoc\Documents\Types\StartImmediateRenderRequestTarget;
use Pogodoc\Documents\Types\StartImmediateRenderResponse;
use Pogodoc\Documents\Types\StartImmediateRenderRequestFormatOpts;
use Pogodoc\Environments;
use PogodocSdk\PogodocUtils;

class PogodocSdk extends PogodocClient
{
    /**
     * Initializes a new instance of the PogodocSdk.
     * The PogodocSdk provides a high-level interface to the Pogodoc API,
     * simplifying template management and document generation.
     * 
     * @param string|null $apiToken The API token for authentication. If null, will use POGODOC_API_TOKEN environment variable.
     * @param array $config Configuration options including baseUrl.
     * @throws \InvalidArgumentException If the API token is not provided and POGODOC_API_TOKEN environment variable is not set.
     */
    public function __construct(?string $apiToken = null, array $config = [])
    {
        if( $apiToken === null && !isset($_ENV['POGODOC_API_TOKEN'])) {
            throw new \InvalidArgumentException("API token is required. Please provide it either as a parameter or set the POGODOC_API_TOKEN environment variable.");
        }
        $config['baseUrl'] = $config['baseUrl'] ?? $_ENV['POGODOC_BASE_URL'] ?? Environments::Default_->value;;
        parent::__construct(
            $apiToken ?? $_ENV['POGODOC_API_TOKEN'],
            $config);
    }

    /**
     * Saves a new template from a local file path.
     * This method reads a template from a .zip file, uploads it, and saves it in Pogodoc.
     * It is a convenient wrapper around `saveTemplateFromFileStream`.
     *
     * @param array $params The properties for saving a template.
     * @param string $params['path'] The local file path to the .zip file containing the template.
     * @param string $params['title'] The title of the template.
     * @param string $params['description'] A description for the template.
     * @param string $params['type'] The type of the template.
     * @param array $params['categories'] Categories for the template.
     * @param mixed $params['sampleData'] Sample data to be used for generating previews.
     * @param string $params['sourceCode'] A link to the source code of the template.
     * @return string The new template's ID.
     */
    public function saveTemplate(array $params): string
    {
        $path = $params['path'];

        $zipStream = fopen($path, 'rb');
        $zipLength = filesize($path);

        return $this->saveTemplateFromFileStream(array_merge([
            'payload' => $zipStream,
            'payloadLength' => $zipLength,
        ], $params));
    }

    /**
     * Saves a new template from a file stream.
     * This is the core method for creating templates. It uploads a template from a stream,
     * generates previews, and saves it with the provided metadata.
     *
     * @param array $params The properties for saving a template from a stream.
     * @param resource $params['payload'] The readable stream of the .zip file.
     * @param int $params['payloadLength'] The length of the payload in bytes.
     * @param string $params['title'] The title of the template.
     * @param string $params['description'] A description for the template.
     * @param string $params['type'] The type of the template.
     * @param array $params['categories'] Categories for the template.
     * @param mixed $params['sampleData'] Sample data to be used for generating previews.
     * @param string $params['sourceCode'] A link to the source code of the template.
     * @return string The new template's ID.
     */
    public function saveTemplateFromFileStream(array $params): string
    {
        $payload = $params['payload'];
        $payloadLength = $params['payloadLength'];

        $init = $this->templates->initializeTemplateCreation();
        $templateId = $init->templateId;

        PogodocUtils::uploadToS3WithUrl($init->presignedTemplateUploadUrl, $payload, $payloadLength, 'application/zip');

        $this->templates->extractTemplateFiles($templateId);

        $request = new GenerateTemplatePreviewsRequest([
            'type' => GenerateTemplatePreviewsRequestType::from($params['type'])->value,
            'data' => $params['sampleData'],
        ]);

        $previews = $this->templates->generateTemplatePreviews($templateId, $request);

        $templateInfo = new SaveCreatedTemplateRequestTemplateInfo([
            'title' => $params['title'],
            'description' => $params['description'],
            'type' => SaveCreatedTemplateRequestTemplateInfoType::from($params['type'])->value,
            'categories' => $params['categories'],
            'sampleData' => $params['sampleData'],
            'sourceCode' => $params['sourceCode'] ?? '',
        ]);

        $previewIds = new SaveCreatedTemplateRequestPreviewIds([
            'pngJobId' => $previews->pngPreview->jobId,
            'pdfJobId' => $previews->pdfPreview->jobId,
        ]);

        $test = new SaveCreatedTemplateRequest([
            'templateInfo' => $templateInfo,
            'previewIds' => $previewIds,
        ]);
    
        $this->templates->saveCreatedTemplate($templateId, $test);

        return $templateId;
    }

    /**
     * Updates an existing template from a local file path.
     * This method reads a new version of a template from a .zip file, uploads it,
     * and updates the existing template in Pogodoc.
     * It is a convenient wrapper around `updateTemplateFromFileStream`.
     *
     * @param array $params The properties for updating a template.
     * @param string $params['path'] The local file path to the .zip file containing the new template version.
     * @param string $params['templateId'] The ID of the template to update.
     * @param string $params['title'] The new title of the template.
     * @param string $params['description'] A new description for the template.
     * @param string $params['type'] The new type of the template.
     * @param array $params['categories'] New categories for the template.
     * @param mixed $params['sampleData'] New sample data to be used for generating previews.
     * @param string $params['sourceCode'] A new link to the source code of the template.
     * @return string The content ID of the new template version.
     */
    public function updateTemplate(array $params): string
    {
        $path = $params['path'];

        $zipStream = fopen($path, 'rb');
        $zipLength = filesize($path);

        return $this->updateTemplateFromFileStream(array_merge([
            'payload' => $zipStream,
            'payloadLength' => $zipLength,
        ], $params));
    }

    /**
     * Updates an existing template from a file stream.
     * This is the core method for updating templates. It uploads a new template version from a stream,
     * generates new previews, and updates the template with the provided metadata.
     *
     * @param array $params The properties for updating a template from a stream.
     * @param string $params['templateId'] The ID of the template to update.
     * @param resource $params['payload'] The readable stream of the .zip file with the new template version.
     * @param int $params['payloadLength'] The length of the payload in bytes.
     * @param string $params['title'] The new title of the template.
     * @param string $params['description'] A new description for the template.
     * @param string $params['type'] The new type of the template.
     * @param array $params['categories'] New categories for the template.
     * @param mixed $params['sampleData'] New sample data to be used for generating previews.
     * @param string $params['sourceCode'] A new link to the source code of the template.
     * @return string The content ID of the new template version.
     */
    public function updateTemplateFromFileStream(array $params): string
    {
        $payload = $params['payload'];
        $payloadLength = $params['payloadLength'];

        $init = $this->templates->initializeTemplateCreation();
        $contentId = $init->templateId;

        PogodocUtils::uploadToS3WithUrl($init->presignedTemplateUploadUrl, $payload, $payloadLength, 'application/zip');

        $this->templates->extractTemplateFiles($contentId);

        $previewRequest = new GenerateTemplatePreviewsRequest ([
            'type' => GenerateTemplatePreviewsRequestType::from($params['type'])->value,
            'data' => $params['sampleData'],
        ]);

        $previews = $this->templates->generateTemplatePreviews($contentId, $previewRequest);

        $templateInfo = new UpdateTemplateRequestTemplateInfo([
            'title' => $params['title'],
            'description' => $params['description'],
            'type' => UpdateTemplateRequestTemplateInfoType::from($params['type'])->value,
            'categories' => $params['categories'],
            'sampleData' => $params['sampleData'],
            'sourceCode' => $params['sourceCode'] ?? "",
        ]);

        $previewIds = new UpdateTemplateRequestPreviewIds([
            'pngJobId' => $previews->pngPreview->jobId,
            'pdfJobId' => $previews->pdfPreview->jobId,
        ]);


        $updateTemplateRequest = new UpdateTemplateRequest([
            'contentId' => $contentId,
            'templateInfo' => $templateInfo,
            'previewIds' => $previewIds,
        ]);

        $this->templates->updateTemplate($params['templateId'], $updateTemplateRequest);

        return $contentId;
    }

    /**
     * Generates a document and returns the result immediately.
     * Use this method for quick, synchronous rendering of small documents.
     * The result is returned directly in the response.
     * For larger documents or when you need to handle rendering asynchronously, use `generateDocument`.
     *
     * You must provide either a `templateId` of a saved template or a `template` string.
     *
     * @param array $params The properties for generating a document.
     * @param string $params['templateId'] The ID of the template to use for rendering.
     * @param string $params['template'] The raw HTML template string to use for rendering.
     * @param mixed $params['data'] The data to populate the template with.
     * @param array $params['renderConfig'] Configuration for the rendering process.
     * @param string $params['renderConfig']['type'] The type of rendering.
     * @param string $params['renderConfig']['target'] The target format.
     * @param array $params['renderConfig']['formatOpts'] Additional format options.
     * @return StartImmediateRenderResponse The presigned url of the generated document.
     */
    public function generateDocumentImmediate(array $params): StartImmediateRenderResponse
    {
        $startImmediateRenderRequest = new StartImmediateRenderRequest([
            'template'    => $params['template'] ?? "",
            'templateId'  => $params['templateId'] ?? "",
            'type'        => StartImmediateRenderRequestType::from($params['renderConfig']['type'])->value,
            'target'      => StartImmediateRenderRequestTarget::from($params['renderConfig']['target'])->value,
            'formatOpts'  => $params['renderConfig']['formatOpts'] ? new StartImmediateRenderRequestFormatOpts($params['renderConfig']['formatOpts']) : null,
            'data' => $params['data'],
        ]);

        return $this->documents->startImmediateRender($startImmediateRenderRequest);
    }

    /**
     * Generates a document by starting a job and polling for its completion.
     * This is the recommended method for most use cases, especially for larger documents or when you want a simple fire-and-forget operation.
     * It first calls `startGenerateDocument` to begin the process, then `pollForJobCompletion` to wait for the result.
     *
     * You must provide either a `templateId` of a saved template or a `template` string.
     *
     * @param array $params The properties for generating a document.
     * @param string $params['templateId'] The ID of the template to use for rendering.
     * @param string $params['template'] The raw HTML template string to use for rendering.
     * @param mixed $params['data'] The data to populate the template with.
     * @param array $params['renderConfig'] Configuration for the rendering process.
     * @param string $params['renderConfig']['type'] The type of rendering.
     * @param string $params['renderConfig']['target'] The target format.
     * @param array $params['renderConfig']['formatOpts'] Additional format options.
     * @return GetJobStatusResponse The final job status, including the output URL.
     */
    public function generateDocument(array $params): GetJobStatusResponse{
        $jobId = $this->startGenerateDocument($params);

        return $this->pollForJobCompletion($jobId);
    }

    /**
     * Starts an asynchronous document generation job.
     * This is a lower-level method that only initializes the job.
     * You can use this if you want to implement your own polling logic.
     * It returns the initial job status, which includes the jobId.
     * Use `pollForJobCompletion` with the jobId to get the final result.
     *
     * You must provide either a `templateId` of a saved template or a `template` string.
     *
     * @param array $params The properties for generating a document.
     * @param string $params['templateId'] The ID of the template to use for rendering.
     * @param string $params['template'] The raw HTML template string to use for rendering.
     * @param mixed $params['data'] The data to populate the template with.
     * @param array $params['renderConfig'] Configuration for the rendering process.
     * @param string $params['renderConfig']['type'] The type of rendering.
     * @param string $params['renderConfig']['target'] The target format.
     * @param array $params['renderConfig']['formatOpts'] Additional format options.
     * @return string The job ID of the generated document.
     */
    public function startGenerateDocument(array $params): string
    {
        $template = $params['template'] ?? "";
        $templateId = $params['templateId'] ?? "";  
        $data = $params['data'];
        $renderConfig = $params['renderConfig'];

        $initRequest = new InitializeRenderJobRequest([
            'type' => InitializeRenderJobRequestType::from($renderConfig['type'])->value,
            'target' => InitializeRenderJobRequestTarget::from($renderConfig['target'])->value,
            'templateId' => $templateId,
            'formatOpts' => $renderConfig['formatOpts'] ? new InitializeRenderJobRequestFormatOpts($renderConfig['formatOpts']) : null,
        ]);

        $initResponse = $this->documents->initializeRenderJob($initRequest);

        $dataString = json_encode($data);
        $dataStream = fopen('php://temp', 'r+');
        fwrite($dataStream, $dataString);
        rewind($dataStream);

        if (!empty($initResponse->presignedDataUploadUrl)) {
            PogodocUtils::uploadToS3WithUrl(
                $initResponse->presignedDataUploadUrl,
                $dataStream,
                strlen($dataString),
                'application/json'
            );
        }

        if (!empty($template) && !empty($initResponse->presignedTemplateUploadUrl)) {
            $templateStream = fopen('php://temp', 'r+');
            fwrite($templateStream, $template);
            rewind($templateStream);

            PogodocUtils::uploadToS3WithUrl(
                $initResponse->presignedTemplateUploadUrl,
                $templateStream,
                strlen($template),
                'text/html'
            );
        }

        $startRenderJobRequest = new StartRenderJobRequest([
            'uploadPresignedS3Url' => $renderConfig->personalUploadPresignedS3Url ?? null,
            ]);

        $response = $this->documents->startRenderJob($initResponse->jobId, $startRenderJobRequest);

        return $response->jobId;
    }

    /**
     * Polls for the completion of a rendering job.
     * This method repeatedly checks the status of a job until it is 'done'.
     *
     * @param string $jobId The ID of the job to poll.
     * @param int $maxAttempts The maximum number of polling attempts (default: 60).
     * @param int $intervalMs The interval in milliseconds between polling attempts (default: 500).
     * @return GetJobStatusResponse The final job status.
     */
    public function pollForJobCompletion(string $jobId, int $maxAttempts = 60, int $intervalMs = 500): GetJobStatusResponse
    {
        usleep(1000 * 1000);
        for ($i = 0; $i < $maxAttempts; $i++) {
            $job = $this->documents->getJobStatus($jobId);
            if ($job->status === 'done') {
                return $job;
            }

            usleep($intervalMs * 1000);
        }

       return $this->documents->getJobStatus($jobId);
    }
}
