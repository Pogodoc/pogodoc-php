<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class StartRenderJobResponseErrorOutput extends JsonSerializableType
{
    /**
     * @var StartRenderJobResponseErrorOutputData $data
     */
    #[JsonProperty('data')]
    public StartRenderJobResponseErrorOutputData $data;

    /**
     * @var StartRenderJobResponseErrorOutputMetadata $metadata
     */
    #[JsonProperty('metadata')]
    public StartRenderJobResponseErrorOutputMetadata $metadata;

    /**
     * @param array{
     *   data: StartRenderJobResponseErrorOutputData,
     *   metadata: StartRenderJobResponseErrorOutputMetadata,
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
