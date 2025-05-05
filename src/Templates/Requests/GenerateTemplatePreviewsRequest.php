<?php

namespace Pogodoc\Templates\Requests;

use Pogodoc\Templates\Types\GenerateTemplatePreviewsRequestType;
use Pogodoc\Core\JsonProperty;
use Pogodoc\Core\ArrayType;
use Pogodoc\Templates\Types\GenerateTemplatePreviewsRequestFormatOpts;

class GenerateTemplatePreviewsRequest
{
    /**
     * @var GenerateTemplatePreviewsRequestType $type
     */
    #[JsonProperty('type')]
    public GenerateTemplatePreviewsRequestType $type;

    /**
     * @var array<string, mixed> $data
     */
    #[JsonProperty('data'), ArrayType(['string' => 'mixed'])]
    public array $data;

    /**
     * @var ?GenerateTemplatePreviewsRequestFormatOpts $formatOpts
     */
    #[JsonProperty('formatOpts')]
    public ?GenerateTemplatePreviewsRequestFormatOpts $formatOpts;

    /**
     * @param array{
     *   type: GenerateTemplatePreviewsRequestType,
     *   data: array<string, mixed>,
     *   formatOpts?: ?GenerateTemplatePreviewsRequestFormatOpts,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->type = $values['type'];
        $this->data = $values['data'];
        $this->formatOpts = $values['formatOpts'] ?? null;
    }
}
