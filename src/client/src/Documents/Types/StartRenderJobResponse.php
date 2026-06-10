<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class StartRenderJobResponse extends JsonSerializableType
{
    /**
     * @var string $jobId ID of the render job
     */
    #[JsonProperty('jobId')]
    public string $jobId;

    /**
     * @var ?value-of<StartRenderJobResponseTarget> $target Type of output to be rendered
     */
    #[JsonProperty('target')]
    public ?string $target;

    /**
     * @var ?string $status Status of the render job
     */
    #[JsonProperty('status')]
    public ?string $status;

    /**
     * @var ?bool $success Whether the render job was successful
     */
    #[JsonProperty('success')]
    public ?bool $success;

    /**
     * @var ?StartRenderJobResponseOutput $output
     */
    #[JsonProperty('output')]
    public ?StartRenderJobResponseOutput $output;

    /**
     * @var ?string $error Error that occurred during render
     */
    #[JsonProperty('error')]
    public ?string $error;

    /**
     * @param array{
     *   jobId: string,
     *   target?: ?value-of<StartRenderJobResponseTarget>,
     *   status?: ?string,
     *   success?: ?bool,
     *   output?: ?StartRenderJobResponseOutput,
     *   error?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->jobId = $values['jobId'];
        $this->target = $values['target'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->success = $values['success'] ?? null;
        $this->output = $values['output'] ?? null;
        $this->error = $values['error'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
