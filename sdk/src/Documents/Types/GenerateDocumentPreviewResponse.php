<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class GenerateDocumentPreviewResponse extends SerializableType
{
    /**
     * @var ?string $url
     */
    #[JsonProperty('url')]
    public ?string $url;

    /**
     * @var string $jobId
     */
    #[JsonProperty('jobId')]
    public string $jobId;

    /**
     * @param array{
     *   url?: ?string,
     *   jobId: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->url = $values['url'] ?? null;
        $this->jobId = $values['jobId'];
    }
}
