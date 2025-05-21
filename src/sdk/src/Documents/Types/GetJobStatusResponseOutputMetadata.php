<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class GetJobStatusResponseOutputMetadata extends JsonSerializableType
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

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
