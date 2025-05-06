<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class GetTemplateIndexHtmlResponse extends SerializableType
{
    /**
     * @var string $templateIndex
     */
    #[JsonProperty('templateIndex')]
    public string $templateIndex;

    /**
     * @param array{
     *   templateIndex: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->templateIndex = $values['templateIndex'];
    }
}
