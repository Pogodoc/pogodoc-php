<?php

namespace Pogodoc\Render\Requests;

use Pogodoc\Core\JsonProperty;
use Pogodoc\Core\ArrayType;
use Pogodoc\Render\Types\InitializeRenderJobRequestType;
use Pogodoc\Render\Types\InitializeRenderJobRequestTarget;
use Pogodoc\Render\Types\InitializeRenderJobRequestFormatOpts;

class InitializeRenderJobRequest
{
    /**
     * @var ?array<string, mixed> $data
     */
    #[JsonProperty('data'), ArrayType(['string' => 'mixed'])]
    public ?array $data;

    /**
     * @var InitializeRenderJobRequestType $type
     */
    #[JsonProperty('type')]
    public InitializeRenderJobRequestType $type;

    /**
     * @var InitializeRenderJobRequestTarget $target
     */
    #[JsonProperty('target')]
    public InitializeRenderJobRequestTarget $target;

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
     *   data?: ?array<string, mixed>,
     *   type: InitializeRenderJobRequestType,
     *   target: InitializeRenderJobRequestTarget,
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
