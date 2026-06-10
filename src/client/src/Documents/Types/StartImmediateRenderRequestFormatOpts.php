<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

/**
 * Format options for the rendered document
 */
class StartImmediateRenderRequestFormatOpts extends JsonSerializableType
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
     * @var ?value-of<StartImmediateRenderRequestFormatOptsFormat> $format
     */
    #[JsonProperty('format')]
    public ?string $format;

    /**
     * @var ?string $waitForSelector Selector to wait for to know when the page is loaded and can be saved as pdf, png, etc.
     */
    #[JsonProperty('waitForSelector')]
    public ?string $waitForSelector;

    /**
     * @var ?value-of<StartImmediateRenderRequestFormatOptsOrientation> $orientation
     */
    #[JsonProperty('orientation')]
    public ?string $orientation;

    /**
     * @var ?StartImmediateRenderRequestFormatOptsDimensions $dimensions
     */
    #[JsonProperty('dimensions')]
    public ?StartImmediateRenderRequestFormatOptsDimensions $dimensions;

    /**
     * @param array{
     *   fromPage?: ?float,
     *   toPage?: ?float,
     *   format?: ?value-of<StartImmediateRenderRequestFormatOptsFormat>,
     *   waitForSelector?: ?string,
     *   orientation?: ?value-of<StartImmediateRenderRequestFormatOptsOrientation>,
     *   dimensions?: ?StartImmediateRenderRequestFormatOptsDimensions,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->fromPage = $values['fromPage'] ?? null;
        $this->toPage = $values['toPage'] ?? null;
        $this->format = $values['format'] ?? null;
        $this->waitForSelector = $values['waitForSelector'] ?? null;
        $this->orientation = $values['orientation'] ?? null;
        $this->dimensions = $values['dimensions'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
