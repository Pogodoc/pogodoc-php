<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class GetJobStatusResponse extends SerializableType
{
    /**
     * @var GetJobStatusResponseType $type
     */
    #[JsonProperty('type')]
    public GetJobStatusResponseType $type;

    /**
     * @var string $jobId
     */
    #[JsonProperty('jobId')]
    public string $jobId;

    /**
     * @var GetJobStatusResponseTarget $target
     */
    #[JsonProperty('target')]
    public GetJobStatusResponseTarget $target;

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
     *   type: GetJobStatusResponseType,
     *   jobId: string,
     *   target: GetJobStatusResponseTarget,
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
}
