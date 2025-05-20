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
     * @var ?array<string, mixed> $data
     */
    #[JsonProperty('data'), ArrayType(['string' => 'mixed'])]
    public ?array $data;

    /**
     * @var value-of<InitializeRenderJobRequestType> $type
     */
    #[JsonProperty('type')]
    public string $type;

    /**
     * @var value-of<InitializeRenderJobRequestTarget> $target
     */
    #[JsonProperty('target')]
    public string $target;

    /**
     * @var ?string $templateId
     */
    #[JsonProperty('templateId')]
    public ?string $templateId;

    /**
     * @var ?InitializeRenderJobRequestFormatOpts $formatOpts
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
