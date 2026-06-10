<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class StartImmediateRenderRequestFormatOptsDimensions extends JsonSerializableType
{
    /**
     * @var float $width
     */
    #[JsonProperty('width')]
    public float $width;

    /**
     * @var float $height
     */
    #[JsonProperty('height')]
    public float $height;

    /**
     * @param array{
     *   width: float,
     *   height: float,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->width = $values['width'];
        $this->height = $values['height'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
