<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class InitializeTemplateCreationResponse extends SerializableType
{
    /**
     * @var string $jobId
     */
    #[JsonProperty('jobId')]
    public string $jobId;

    /**
     * @var string $presignedTemplateUploadUrl
     */
    #[JsonProperty('presignedTemplateUploadUrl')]
    public string $presignedTemplateUploadUrl;

    /**
     * @param array{
     *   jobId: string,
     *   presignedTemplateUploadUrl: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->jobId = $values['jobId'];
        $this->presignedTemplateUploadUrl = $values['presignedTemplateUploadUrl'];
    }
}
