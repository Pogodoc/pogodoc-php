<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class GeneratePresignedGetUrlResponse extends SerializableType
{
    /**
     * @var string $presignedUrl
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
}
