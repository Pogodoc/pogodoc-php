<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class GetJobStatusResponseOutputMetadata extends SerializableType
{
    /**
     * @var float $renderTime
     */
    #[JsonProperty('renderTime')]
    public float $renderTime;

    /**
     * @param array{
     *   renderTime: float,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->renderTime = $values['renderTime'];
    }
}
