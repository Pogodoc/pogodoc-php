<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class InitializeTemplateCreationResponse extends JsonSerializableType
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

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
