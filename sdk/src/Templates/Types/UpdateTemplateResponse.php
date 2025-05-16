<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class UpdateTemplateResponse extends JsonSerializableType
{
    /**
     * @var string $newContentId
     */
    #[JsonProperty('newContentId')]
    public string $newContentId;

    /**
     * @param array{
     *   newContentId: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->newContentId = $values['newContentId'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
