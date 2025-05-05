<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class GenerateTemplatePreviewsResponse extends SerializableType
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
}
