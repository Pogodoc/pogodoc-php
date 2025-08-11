<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class StartRenderJobResponse extends JsonSerializableType
{
    /**
     * @var string $jobId ID of the render job
     */
    #[JsonProperty('jobId')]
    public string $jobId;

    /**
     * @var ?string $templateId ID of the template being used
     */
    #[JsonProperty('templateId')]
    public ?string $templateId;

    /**
     * @var ?value-of<StartRenderJobResponseTarget> $target Type of output to be rendered
     */
    #[JsonProperty('target')]
    public ?string $target;

    /**
     * @var ?string $uploadPresignedS3Url Presigned URL to upload the rendered output to S3
     */
    #[JsonProperty('uploadPresignedS3Url')]
    public ?string $uploadPresignedS3Url;

    /**
     * @var ?StartRenderJobResponseFormatOpts $formatOpts Format options for the rendered document
     */
    #[JsonProperty('formatOpts')]
    public ?StartRenderJobResponseFormatOpts $formatOpts;

    /**
     * @var ?string $status Status of the render job
     */
    #[JsonProperty('status')]
    public ?string $status;

    /**
     * @var ?bool $success Whether the render job was successful
     */
    #[JsonProperty('success')]
    public ?bool $success;

    /**
     * @var ?StartRenderJobResponseOutput $output
     */
    #[JsonProperty('output')]
    public ?StartRenderJobResponseOutput $output;

    /**
     * @var ?string $error Error that occurred during render
     */
    #[JsonProperty('error')]
    public ?string $error;

    /**
     * @param array{
     *   jobId: string,
     *   templateId?: ?string,
     *   target?: ?value-of<StartRenderJobResponseTarget>,
     *   uploadPresignedS3Url?: ?string,
     *   formatOpts?: ?StartRenderJobResponseFormatOpts,
     *   status?: ?string,
     *   success?: ?bool,
     *   output?: ?StartRenderJobResponseOutput,
     *   error?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->jobId = $values['jobId'];
        $this->templateId = $values['templateId'] ?? null;
        $this->target = $values['target'] ?? null;
        $this->uploadPresignedS3Url = $values['uploadPresignedS3Url'] ?? null;
        $this->formatOpts = $values['formatOpts'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->success = $values['success'] ?? null;
        $this->output = $values['output'] ?? null;
        $this->error = $values['error'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
