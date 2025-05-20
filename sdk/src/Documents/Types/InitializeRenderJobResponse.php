<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class InitializeRenderJobResponse extends JsonSerializableType
{
    /**
     * @var string $jobId
     */
    #[JsonProperty('jobId')]
    public string $jobId;

    /**
     * @var value-of<InitializeRenderJobResponseTarget> $target
     */
    #[JsonProperty('target')]
    public string $target;

    /**
     * @var ?string $presignedDataUploadUrl
     */
    #[JsonProperty('presignedDataUploadUrl')]
    public ?string $presignedDataUploadUrl;

    /**
     * @var ?string $presignedTemplateUploadUrl
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
