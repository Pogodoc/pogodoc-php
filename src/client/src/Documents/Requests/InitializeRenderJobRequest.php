<?php

namespace Pogodoc\Documents\Requests;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;
use Pogodoc\Core\Types\ArrayType;
use Pogodoc\Documents\Types\InitializeRenderJobRequestType;
use Pogodoc\Documents\Types\InitializeRenderJobRequestTarget;
use Pogodoc\Documents\Types\InitializeRenderJobRequestFormatOpts;

class InitializeRenderJobRequest extends JsonSerializableType
{
    /**
     * @var ?array<string, mixed> $data Sample data for the template
     */
    #[JsonProperty('data'), ArrayType(['string' => 'mixed'])]
    public ?array $data;

    /**
     * @var value-of<InitializeRenderJobRequestType> $type Type of template to be rendered
     */
    #[JsonProperty('type')]
    public string $type;

    /**
     * @var value-of<InitializeRenderJobRequestTarget> $target Type of output to be rendered
     */
    #[JsonProperty('target')]
    public string $target;

    /**
     * @var ?string $templateId ID of the template to be used
     */
    #[JsonProperty('templateId')]
    public ?string $templateId;

    /**
     * @var ?InitializeRenderJobRequestFormatOpts $formatOpts Format options for the rendered document
     */
    #[JsonProperty('formatOpts')]
    public ?InitializeRenderJobRequestFormatOpts $formatOpts;

    /**
     * @param array{
     *   type: value-of<InitializeRenderJobRequestType>,
     *   target: value-of<InitializeRenderJobRequestTarget>,
     *   data?: ?array<string, mixed>,
     *   templateId?: ?string,
     *   formatOpts?: ?InitializeRenderJobRequestFormatOpts,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->data = $values['data'] ?? null;
        $this->type = $values['type'];
        $this->target = $values['target'];
        $this->templateId = $values['templateId'] ?? null;
        $this->formatOpts = $values['formatOpts'] ?? null;
    }
}
