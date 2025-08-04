<?php

namespace Pogodoc\Documents\Requests;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Documents\Types\StartImmediateRenderRequestType;
use Pogodoc\Core\Json\JsonProperty;
use Pogodoc\Documents\Types\StartImmediateRenderRequestTarget;
use Pogodoc\Documents\Types\StartImmediateRenderRequestFormatOpts;
use Pogodoc\Core\Types\ArrayType;

class StartImmediateRenderRequest extends JsonSerializableType
{
    /**
     * @var value-of<StartImmediateRenderRequestType> $type Type of template to be rendered
     */
    #[JsonProperty('type')]
    public string $type;

    /**
     * @var value-of<StartImmediateRenderRequestTarget> $target Type of output to be rendered
     */
    #[JsonProperty('target')]
    public string $target;

    /**
     * @var ?string $templateId ID of the template to be used
     */
    #[JsonProperty('templateId')]
    public ?string $templateId;

    /**
     * @var ?StartImmediateRenderRequestFormatOpts $formatOpts Format options for the rendered document
     */
    #[JsonProperty('formatOpts')]
    public ?StartImmediateRenderRequestFormatOpts $formatOpts;

    /**
     * @var array<string, mixed> $data Sample data for the template
     */
    #[JsonProperty('data'), ArrayType(['string' => 'mixed'])]
    public array $data;

    /**
     * @var ?string $template index.html or ejs file of the template as a string
     */
    #[JsonProperty('template')]
    public ?string $template;

    /**
     * @var ?string $uploadPresignedS3Url Presigned URL to upload the data for the render job to S3
     */
    #[JsonProperty('uploadPresignedS3Url')]
    public ?string $uploadPresignedS3Url;

    /**
     * @param array{
     *   type: value-of<StartImmediateRenderRequestType>,
     *   target: value-of<StartImmediateRenderRequestTarget>,
     *   data: array<string, mixed>,
     *   templateId?: ?string,
     *   formatOpts?: ?StartImmediateRenderRequestFormatOpts,
     *   template?: ?string,
     *   uploadPresignedS3Url?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->type = $values['type'];
        $this->target = $values['target'];
        $this->templateId = $values['templateId'] ?? null;
        $this->formatOpts = $values['formatOpts'] ?? null;
        $this->data = $values['data'];
        $this->template = $values['template'] ?? null;
        $this->uploadPresignedS3Url = $values['uploadPresignedS3Url'] ?? null;
    }
}
