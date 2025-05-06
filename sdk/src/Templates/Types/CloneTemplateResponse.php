<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class CloneTemplateResponse extends SerializableType
{
    /**
     * @var string $newTemplateId
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
}
