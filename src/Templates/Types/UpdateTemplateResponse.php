<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class UpdateTemplateResponse extends SerializableType
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
}
