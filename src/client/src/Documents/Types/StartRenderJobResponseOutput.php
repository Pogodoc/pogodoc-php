<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class StartRenderJobResponseOutput extends JsonSerializableType
{
    /**
     * @var StartRenderJobResponseOutputData $data
     */
    #[JsonProperty('data')]
    public StartRenderJobResponseOutputData $data;

    /**
     * @var StartRenderJobResponseOutputMetadata $metadata
     */
    #[JsonProperty('metadata')]
    public StartRenderJobResponseOutputMetadata $metadata;

    /**
     * @param array{
     *   data: StartRenderJobResponseOutputData,
     *   metadata: StartRenderJobResponseOutputMetadata,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->data = $values['data'];
        $this->metadata = $values['metadata'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
