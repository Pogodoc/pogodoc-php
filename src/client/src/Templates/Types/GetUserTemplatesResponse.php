<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;
use Pogodoc\Core\Types\ArrayType;

class GetUserTemplatesResponse extends JsonSerializableType
{
    /**
     * @var array<GetUserTemplatesResponseTemplatesItem> $templates
     */
    #[JsonProperty('templates'), ArrayType([GetUserTemplatesResponseTemplatesItem::class])]
    public array $templates;

    /**
     * @param array{
     *   templates: array<GetUserTemplatesResponseTemplatesItem>,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->templates = $values['templates'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
