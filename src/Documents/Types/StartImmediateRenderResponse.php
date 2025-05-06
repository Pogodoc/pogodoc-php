<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class StartImmediateRenderResponse extends SerializableType
{
    /**
     * @var string $url
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
}
