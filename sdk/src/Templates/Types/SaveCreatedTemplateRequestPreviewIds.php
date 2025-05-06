<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class SaveCreatedTemplateRequestPreviewIds extends SerializableType
{
    /**
     * @var string $pngJobId
     */
    #[JsonProperty('pngJobId')]
    public string $pngJobId;

    /**
     * @var string $pdfJobId
     */
    #[JsonProperty('pdfJobId')]
    public string $pdfJobId;

    /**
     * @param array{
     *   pngJobId: string,
     *   pdfJobId: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->pngJobId = $values['pngJobId'];
        $this->pdfJobId = $values['pdfJobId'];
    }
}
