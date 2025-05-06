<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class InitializeRenderJobResponse extends SerializableType
{
    /**
     * @var string $jobId
     */
    #[JsonProperty('jobId')]
    public string $jobId;

    /**
     * @var InitializeRenderJobResponseTarget $target
     */
    #[JsonProperty('target')]
    public InitializeRenderJobResponseTarget $target;

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
     *   target: InitializeRenderJobResponseTarget,
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
}
