<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class GenerateTemplatePreviewsResponsePdfPreview extends JsonSerializableType
{
    /**
     * @var string $url URL of the rendered preview
     */
    #[JsonProperty('url')]
    public string $url;

    /**
     * @var string $jobId ID of the render job
     */
    #[JsonProperty('jobId')]
    public string $jobId;

    /**
     * @param array{
     *   url: string,
     *   jobId: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->url = $values['url'];
        $this->jobId = $values['jobId'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
