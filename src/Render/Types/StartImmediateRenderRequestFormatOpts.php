<?php

namespace Pogodoc\Render\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class StartImmediateRenderRequestFormatOpts extends SerializableType
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
     * @var ?StartImmediateRenderRequestFormatOptsFormat $format
     */
    #[JsonProperty('format')]
    public ?StartImmediateRenderRequestFormatOptsFormat $format;

    /**
     * @var ?string $waitForSelector
     */
    #[JsonProperty('waitForSelector')]
    public ?string $waitForSelector;

    /**
     * @param array{
     *   fromPage?: ?float,
     *   toPage?: ?float,
     *   format?: ?StartImmediateRenderRequestFormatOptsFormat,
     *   waitForSelector?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->fromPage = $values['fromPage'] ?? null;
        $this->toPage = $values['toPage'] ?? null;
        $this->format = $values['format'] ?? null;
        $this->waitForSelector = $values['waitForSelector'] ?? null;
    }
}
