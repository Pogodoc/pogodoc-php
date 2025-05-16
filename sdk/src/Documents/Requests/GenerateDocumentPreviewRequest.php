<?php

namespace Pogodoc\Documents\Requests;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Documents\Types\GenerateDocumentPreviewRequestType;
use Pogodoc\Core\Json\JsonProperty;
use Pogodoc\Core\Types\ArrayType;
use Pogodoc\Documents\Types\GenerateDocumentPreviewRequestFormatOpts;

class GenerateDocumentPreviewRequest extends JsonSerializableType
{
    /**
     * @var string $templateId
     */
    public string $templateId;

    /**
     * @var value-of<GenerateDocumentPreviewRequestType> $type
     */
    #[JsonProperty('type')]
    public string $type;

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
     *   type: value-of<GenerateDocumentPreviewRequestType>,
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
