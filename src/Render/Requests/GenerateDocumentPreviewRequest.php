<?php

namespace Pogodoc\Render\Requests;

use Pogodoc\Render\Types\GenerateDocumentPreviewRequestType;
use Pogodoc\Core\JsonProperty;
use Pogodoc\Core\ArrayType;
use Pogodoc\Render\Types\GenerateDocumentPreviewRequestFormatOpts;

class GenerateDocumentPreviewRequest
{
    /**
     * @var string $templateId
     */
    public string $templateId;

    /**
     * @var GenerateDocumentPreviewRequestType $type
     */
    #[JsonProperty('type')]
    public GenerateDocumentPreviewRequestType $type;

    /**
     * @var array<string, mixed> $data
     */
    #[JsonProperty('data'), ArrayType(['string' => 'mixed'])]
    public array $data;

    /**
     * @var ?GenerateDocumentPreviewRequestFormatOpts $formatOpts
     */
    #[JsonProperty('formatOpts')]
    public ?GenerateDocumentPreviewRequestFormatOpts $formatOpts;

    /**
     * @param array{
     *   templateId: string,
     *   type: GenerateDocumentPreviewRequestType,
     *   data: array<string, mixed>,
     *   formatOpts?: ?GenerateDocumentPreviewRequestFormatOpts,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->templateId = $values['templateId'];
        $this->type = $values['type'];
        $this->data = $values['data'];
        $this->formatOpts = $values['formatOpts'] ?? null;
    }
}
