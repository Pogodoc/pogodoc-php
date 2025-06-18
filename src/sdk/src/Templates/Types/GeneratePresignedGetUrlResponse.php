<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class GeneratePresignedGetUrlResponse extends JsonSerializableType
{
    /**
     * @var string $presignedUrl Presigned URL to get the template
     */
    #[JsonProperty('presignedUrl')]
    public string $presignedUrl;

    /**
     * @param array{
     *   presignedUrl: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->presignedUrl = $values['presignedUrl'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
