<?php

namespace Pogodoc\Documents\Requests;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;
use Pogodoc\Core\Types\ArrayType;
use Pogodoc\Documents\Types\StartImmediateRenderRequestType;
use Pogodoc\Documents\Types\StartImmediateRenderRequestTarget;
use Pogodoc\Documents\Types\StartImmediateRenderRequestFormatOpts;

class StartImmediateRenderRequest extends JsonSerializableType
{
    /**
     * @var array<string, mixed> $startImmediateRenderRequestData
     */
    #[JsonProperty('data'), ArrayType(['string' => 'mixed'])]
    public array $startImmediateRenderRequestData;

    /**
     * @var value-of<StartImmediateRenderRequestType> $type
     */
    #[JsonProperty('type')]
    public string $type;

    /**
     * @var value-of<StartImmediateRenderRequestTarget> $target
     */
    #[JsonProperty('target')]
    public string $target;

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
     *   type: value-of<StartImmediateRenderRequestType>,
     *   target: value-of<StartImmediateRenderRequestTarget>,
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
