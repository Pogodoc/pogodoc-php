<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class StartRenderJobResponseErrorOutputData extends JsonSerializableType
{
    /**
     * @var string $url URL of the rendered output
     */
    #[JsonProperty('url')]
    public string $url;

    /**
     * @param array{
     *   url: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->url = $values['url'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
