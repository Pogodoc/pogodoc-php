<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class StartRenderJobResponseOne extends JsonSerializableType
{
    /**
     * @var string $jobId ID of the render job
     */
    #[JsonProperty('jobId')]
    public string $jobId;

    /**
     * @param array{
     *   jobId: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->jobId = $values['jobId'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
