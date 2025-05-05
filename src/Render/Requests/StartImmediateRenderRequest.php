<?php

namespace Pogodoc\Render\Requests;

use Pogodoc\Core\JsonProperty;
use Pogodoc\Core\ArrayType;
use Pogodoc\Render\Types\StartImmediateRenderRequestType;
use Pogodoc\Render\Types\StartImmediateRenderRequestTarget;
use Pogodoc\Render\Types\StartImmediateRenderRequestFormatOpts;

class StartImmediateRenderRequest
{
    /**
     * @var array<string, mixed> $startImmediateRenderRequestData
     */
    #[JsonProperty('data'), ArrayType(['string' => 'mixed'])]
    public array $startImmediateRenderRequestData;

    /**
     * @var StartImmediateRenderRequestType $type
     */
    #[JsonProperty('type')]
    public StartImmediateRenderRequestType $type;

    /**
     * @var StartImmediateRenderRequestTarget $target
     */
    #[JsonProperty('target')]
    public StartImmediateRenderRequestTarget $target;

    /**
     * @var ?string $templateId
     */
    #[JsonProperty('templateId')]
    public ?string $templateId;

    /**
     * @var ?StartImmediateRenderRequestFormatOpts $formatOpts
     */
    #[JsonProperty('formatOpts')]
    public ?StartImmediateRenderRequestFormatOpts $formatOpts;

    /**
     * @var ?string $template
     */
    #[JsonProperty('template')]
    public ?string $template;

    /**
     * @param array{
     *   startImmediateRenderRequestData: array<string, mixed>,
     *   type: StartImmediateRenderRequestType,
     *   target: StartImmediateRenderRequestTarget,
     *   templateId?: ?string,
     *   formatOpts?: ?StartImmediateRenderRequestFormatOpts,
     *   template?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->startImmediateRenderRequestData = $values['startImmediateRenderRequestData'];
        $this->type = $values['type'];
        $this->target = $values['target'];
        $this->templateId = $values['templateId'] ?? null;
        $this->formatOpts = $values['formatOpts'] ?? null;
        $this->template = $values['template'] ?? null;
    }
}
