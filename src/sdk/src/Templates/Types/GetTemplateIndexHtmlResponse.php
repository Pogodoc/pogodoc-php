<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class GetTemplateIndexHtmlResponse extends JsonSerializableType
{
    /**
     * @var string $templateIndex Index.html file of the template
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

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
