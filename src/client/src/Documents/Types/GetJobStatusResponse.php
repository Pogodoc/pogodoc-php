<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class GetJobStatusResponse extends JsonSerializableType
{
    /**
     * @var value-of<GetJobStatusResponseType> $type Type of template to be rendered
     */
    #[JsonProperty('type')]
    public string $type;

    /**
     * @var string $jobId ID of the render job
     */
    #[JsonProperty('jobId')]
    public string $jobId;

    /**
     * @var value-of<GetJobStatusResponseTarget> $target Type of output to be rendered
     */
    #[JsonProperty('target')]
    public string $target;

    /**
     * @var ?GetJobStatusResponseOutput $output
     */
    #[JsonProperty('output')]
    public ?GetJobStatusResponseOutput $output;

    /**
     * @var ?bool $success
     */
    #[JsonProperty('success')]
    public ?bool $success;

    /**
     * @var ?string $status
     */
    #[JsonProperty('status')]
    public ?string $status;

    /**
     * @param array{
     *   type: value-of<GetJobStatusResponseType>,
     *   jobId: string,
     *   target: value-of<GetJobStatusResponseTarget>,
     *   output?: ?GetJobStatusResponseOutput,
     *   success?: ?bool,
     *   status?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->type = $values['type'];
        $this->jobId = $values['jobId'];
        $this->target = $values['target'];
        $this->output = $values['output'] ?? null;
        $this->success = $values['success'] ?? null;
        $this->status = $values['status'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
