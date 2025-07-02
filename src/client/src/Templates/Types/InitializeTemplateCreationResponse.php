<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class InitializeTemplateCreationResponse extends JsonSerializableType
{
    /**
     * @var string $templateId ID of the template
     */
    #[JsonProperty('templateId')]
    public string $templateId;

    /**
     * @var string $presignedTemplateUploadUrl Presigned URL to upload the template to S3
     */
    #[JsonProperty('presignedTemplateUploadUrl')]
    public string $presignedTemplateUploadUrl;

    /**
     * @param array{
     *   templateId: string,
     *   presignedTemplateUploadUrl: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->templateId = $values['templateId'];
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
