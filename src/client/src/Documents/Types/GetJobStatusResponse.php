<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class GetJobStatusResponse extends JsonSerializableType
{
    /**
     * @var string $jobId ID of the render job
     */
    #[JsonProperty('jobId')]
    public string $jobId;

    /**
     * @var string $target Target of the render job
     */
    #[JsonProperty('target')]
    public string $target;

    /**
     * @var string $status Status of the render job
     */
    #[JsonProperty('status')]
    public string $status;

    /**
     * @var ?bool $success Whether the render job was successful
     */
    #[JsonProperty('success')]
    public ?bool $success;

    /**
     * @var ?GetJobStatusResponseOutput $output
     */
    #[JsonProperty('output')]
    public ?GetJobStatusResponseOutput $output;

    /**
     * @var ?string $error Error that occurred during render
     */
    #[JsonProperty('error')]
    public ?string $error;

    /**
     * @param array{
     *   jobId: string,
     *   target: string,
     *   status: string,
     *   success?: ?bool,
     *   output?: ?GetJobStatusResponseOutput,
     *   error?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->jobId = $values['jobId'];
        $this->target = $values['target'];
        $this->status = $values['status'];
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
