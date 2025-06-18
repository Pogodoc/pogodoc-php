<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class InitializeRenderJobResponse extends JsonSerializableType
{
    /**
     * @var string $jobId ID of the render job
     */
    #[JsonProperty('jobId')]
    public string $jobId;

    /**
     * @var value-of<InitializeRenderJobResponseTarget> $target Type of output to be rendered
     */
    #[JsonProperty('target')]
    public string $target;

    /**
     * @var ?string $presignedDataUploadUrl Presigned URL to upload the data for the render job to S3
     */
    #[JsonProperty('presignedDataUploadUrl')]
    public ?string $presignedDataUploadUrl;

    /**
     * @var ?string $presignedTemplateUploadUrl Presigned URL to upload the template for the render job to S3. Only works with EJS templates
     */
    #[JsonProperty('presignedTemplateUploadUrl')]
    public ?string $presignedTemplateUploadUrl;

    /**
     * @param array{
     *   jobId: string,
     *   target: value-of<InitializeRenderJobResponseTarget>,
     *   presignedDataUploadUrl?: ?string,
     *   presignedTemplateUploadUrl?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->jobId = $values['jobId'];
        $this->target = $values['target'];
        $this->presignedDataUploadUrl = $values['presignedDataUploadUrl'] ?? null;
        $this->presignedTemplateUploadUrl = $values['presignedTemplateUploadUrl'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
