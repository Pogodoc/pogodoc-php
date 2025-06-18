<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class CloneTemplateResponse extends JsonSerializableType
{
    /**
     * @var string $newTemplateId ID of the new template
     */
    #[JsonProperty('newTemplateId')]
    public string $newTemplateId;

    /**
     * @param array{
     *   newTemplateId: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->newTemplateId = $values['newTemplateId'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
