<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class GenerateDocumentPreviewRequestFormatOpts extends JsonSerializableType
{
    /**
     * @var ?float $fromPage
     */
    #[JsonProperty('fromPage')]
    public ?float $fromPage;

    /**
     * @var ?float $toPage
     */
    #[JsonProperty('toPage')]
    public ?float $toPage;

    /**
     * @var ?value-of<GenerateDocumentPreviewRequestFormatOptsFormat> $format
     */
    #[JsonProperty('format')]
    public ?string $format;

    /**
     * @var ?string $waitForSelector
     */
    #[JsonProperty('waitForSelector')]
    public ?string $waitForSelector;

    /**
     * @param array{
     *   fromPage?: ?float,
     *   toPage?: ?float,
     *   format?: ?value-of<GenerateDocumentPreviewRequestFormatOptsFormat>,
     *   waitForSelector?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->fromPage = $values['fromPage'] ?? null;
        $this->toPage = $values['toPage'] ?? null;
        $this->format = $values['format'] ?? null;
        $this->waitForSelector = $values['waitForSelector'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
