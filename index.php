<?php

require_once 'sdk/src/PogodocClient.php';
require_once 'utils.php';

use Pogodoc\PogodocClient;
use Pogodoc\Templates\Requests\GenerateTemplatePreviewsRequest;
use Pogodoc\Templates\Types\GenerateTemplatePreviewsRequestType;
use Pogodoc\Templates\Requests\SaveCreatedTemplateRequest;
use Pogodoc\Templates\Types\SaveCreatedTemplateRequestTemplateInfo;
use Pogodoc\Templates\Types\SaveCreatedTemplateRequestPreviewIds;
use Pogodoc\Templates\Requests\UpdateTemplateRequest;
use Pogodoc\Templates\Types\SaveCreatedTemplateRequestTemplateInfoType;
use Pogodoc\Templates\Types\UpdateTemplateRequestTemplateInfo;
use Pogodoc\Templates\Types\UpdateTemplateRequestPreviewIds;
use Pogodoc\Templates\Types\UpdateTemplateRequestTemplateInfoType;
use Pogodoc\Templates\Types\SaveCreatedTemplateRequestTemplateInfoCategoriesItem;
use Pogodoc\Templates\Types\ExtractTemplateFilesRequest;
use Pogodoc\Documents\Requests\StartRenderJobRequest;
use Pogodoc\Documents\Requests\InitializeRenderJobRequest;
use Pogodoc\Documents\Types\InitializeRenderJobRequestType;
use Pogodoc\Documents\Types\InitializeRenderJobRequestTarget;
use Pogodoc\Documents\Types\InitializeRenderJobRequestFormatOpts;
class PogodocApiClient extends PogodocClient
{

    public function saveTemplate(array $params)
    {
        $path = $params['path'];
        unset($params['path']);

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
        unset($params['payload'], $params['payloadLength']);

        $init = $this->templates->initializeTemplateCreation();
        $templateId = $init->jobId;

        print_r($templateId);

        uploadToS3WithUrl($init->presignedTemplateUploadUrl, $payload, $payloadLength, 'application/zip');

        print_r("uploaded zip\n");

        $this->templates->extractTemplateFiles($templateId);

        print_r("extracted files\n");

        $request = new GenerateTemplatePreviewsRequest([
            'type' => GenerateTemplatePreviewsRequestType::from($params['type']),
            'data' => $params['sampleData'],
        ]);

        $previews = $this->templates->generateTemplatePreviews($templateId, $request);

        $templateInfo = new SaveCreatedTemplateRequestTemplateInfo([
            'title' => $params['title'],
            'description' => $params['description'],
            'type' => SaveCreatedTemplateRequestTemplateInfoType::from($params['type']),
            'categories' => [SaveCreatedTemplateRequestTemplateInfoCategoriesItem::Invoice],
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
        unset($params['path']);

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
        unset($params['payload'], $params['payloadLength']);

        $init = $this->templates->initializeTemplateCreation();
        $contentId = $init->jobId;

        uploadToS3WithUrl($init->presignedTemplateUploadUrl, $payload, $payloadLength, 'application/zip');

        $this->templates->extractTemplateFiles($contentId);

        $previewRequest = new GenerateTemplatePreviewsRequest ([
            'type' => GenerateTemplatePreviewsRequestType::from($params['type']),
            'data' => $params['sampleData'],
        ]);

        $previews = $this->templates->generateTemplatePreviews($contentId, $previewRequest);

        $templateInfo = new UpdateTemplateRequestTemplateInfo([
            'title' => $params['title'],
            'description' => $params['description'],
            'type' => UpdateTemplateRequestTemplateInfoType::from($params['type']),
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
        $templateId = $params['templateId'];
        $data = $params['data'];
        $renderConfig = $params['renderConfig'];
        $shouldWait = $params['shouldWaitForRenderCompletion'];

        $initRequest = new InitializeRenderJobRequest([
            'type' => InitializeRenderJobRequestType::from($renderConfig['type']),
            'target' => InitializeRenderJobRequestTarget::from($renderConfig['target']),
            'templateId' => $templateId,
            'formatOpts' => new InitializeRenderJobRequestFormatOpts($renderConfig['formatOpts']),
        ]);

        $initResponse = $this->documents->initializeRenderJob($initRequest);

        $dataString = json_encode($data);
        $dataStream = fopen('php://temp', 'r+');
        fwrite($dataStream, $dataString);
        rewind($dataStream);

        if (!empty($initResponse['presignedDataUploadUrl'])) {
            uploadToS3WithUrl(
                $initResponse['presignedDataUploadUrl'],
                $dataStream,
                strlen($dataString),
                'application/json'
            );
        }

        if (!empty($template) && !empty($initResponse['presignedTemplateUploadUrl'])) {
            $templateStream = fopen('php://temp', 'r+');
            fwrite($templateStream, $template);
            rewind($templateStream);

            uploadToS3WithUrl(
                $initResponse['presignedTemplateUploadUrl'],
                $templateStream,
                strlen($template),
                'text/html'
            );
        }

        $startRenderJobRequest = new StartRenderJobRequest([
            'shouldWaitForRenderCompletion' => $shouldWait,
            'uploadPresignedS3Url' => $renderConfig['personalUploadPresignedS3Url'],
            ]);

        $this->documents->startRenderJob($initResponse['jobId'], $startRenderJobRequest);

        return $this->documents->getJobStatus($initResponse['jobId']);
    }
}
