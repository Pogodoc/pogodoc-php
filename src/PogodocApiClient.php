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
use Pogodoc\Environments;
use PogodocSdk\PogodocUtils;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

class PogodocApiClient extends PogodocClient
{
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

    public function saveTemplate(array $params)
    {
        $path = $params['path'];

        $zipStream = fopen($path, 'rb');
        $zipLength = filesize($path);

        return $this->saveTemplateFromFileStream(array_merge([
            'payload' => $zipStream,
            'payloadLength' => $zipLength,
        ], $params));
    }

    public function saveTemplateFromFileStream(array $params)
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

    public function updateTemplate(array $params)
    {
        $path = $params['path'];

        $zipStream = fopen($path, 'rb');
        $zipLength = filesize($path);

        return $this->updateTemplateFromFileStream(array_merge([
            'payload' => $zipStream,
            'payloadLength' => $zipLength,
        ], $params));
    }

    public function updateTemplateFromFileStream(array $params)
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

    public function generateDocument(array $params)
    {
        $template = $params['template'] ?? "";
        $templateId = $params['templateId'] ?? "";  
        $data = $params['data'];
        $renderConfig = $params['renderConfig'];
        $shouldWait = $params['shouldWaitForRenderCompletion'];

        $initRequest = new InitializeRenderJobRequest([
            'type' => InitializeRenderJobRequestType::from($renderConfig['type'])->value,
            'target' => InitializeRenderJobRequestTarget::from($renderConfig['target'])->value,
            'templateId' => $templateId,
            'formatOpts' => new InitializeRenderJobRequestFormatOpts($renderConfig['formatOpts'] ?? []),
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
            'shouldWaitForRenderCompletion' => $shouldWait,
            'uploadPresignedS3Url' => $renderConfig->personalUploadPresignedS3Url ?? null,
            ]);

        $this->documents->startRenderJob($initResponse->jobId, $startRenderJobRequest);

        return $this->documents->getJobStatus($initResponse->jobId);
    }
}
