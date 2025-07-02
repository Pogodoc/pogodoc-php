<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class GetTemplateIndexHtmlResponse extends JsonSerializableType
{
    /**
     * @var string $indexHtml Index.html file of the template
     */
    #[JsonProperty('indexHtml')]
    public string $indexHtml;

    /**
     * @param array{
     *   indexHtml: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->indexHtml = $values['indexHtml'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
