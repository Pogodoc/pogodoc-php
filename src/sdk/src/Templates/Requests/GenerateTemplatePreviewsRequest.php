<?php

namespace Pogodoc\Templates\Requests;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Templates\Types\GenerateTemplatePreviewsRequestType;
use Pogodoc\Core\Json\JsonProperty;
use Pogodoc\Core\Types\ArrayType;
use Pogodoc\Templates\Types\GenerateTemplatePreviewsRequestFormatOpts;

class GenerateTemplatePreviewsRequest extends JsonSerializableType
{
    /**
     * @var value-of<GenerateTemplatePreviewsRequestType> $type Type of template to be rendered
     */
    #[JsonProperty('type')]
    public string $type;

    /**
     * @var array<string, mixed> $data Sample data for the template
     */
    #[JsonProperty('data'), ArrayType(['string' => 'mixed'])]
    public array $data;

    /**
     * @var ?GenerateTemplatePreviewsRequestFormatOpts $formatOpts Format options for the rendered document
     */
    #[JsonProperty('formatOpts')]
    public ?GenerateTemplatePreviewsRequestFormatOpts $formatOpts;

    /**
     * @param array{
     *   type: value-of<GenerateTemplatePreviewsRequestType>,
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
