<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class GenerateTemplatePreviewsResponse extends JsonSerializableType
{
    /**
     * @var GenerateTemplatePreviewsResponsePngPreview $pngPreview
     */
    #[JsonProperty('pngPreview')]
    public GenerateTemplatePreviewsResponsePngPreview $pngPreview;

    /**
     * @var GenerateTemplatePreviewsResponsePdfPreview $pdfPreview
     */
    #[JsonProperty('pdfPreview')]
    public GenerateTemplatePreviewsResponsePdfPreview $pdfPreview;

    /**
     * @param array{
     *   pngPreview: GenerateTemplatePreviewsResponsePngPreview,
     *   pdfPreview: GenerateTemplatePreviewsResponsePdfPreview,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->pngPreview = $values['pngPreview'];
        $this->pdfPreview = $values['pdfPreview'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
