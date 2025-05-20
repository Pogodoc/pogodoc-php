<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class UpdateTemplateRequestPreviewIds extends JsonSerializableType
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

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
