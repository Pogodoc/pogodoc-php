<?php

namespace Pogodoc\Render\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class StartRenderJobResponse extends SerializableType
{
    /**
     * @var string $jobId
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
}
