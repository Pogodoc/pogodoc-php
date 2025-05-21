<?php

namespace Pogodoc\Documents\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class GetJobStatusResponseOutput extends JsonSerializableType
{
    /**
     * @var GetJobStatusResponseOutputData $data
     */
    #[JsonProperty('data')]
    public GetJobStatusResponseOutputData $data;

    /**
     * @var GetJobStatusResponseOutputMetadata $metadata
     */
    #[JsonProperty('metadata')]
    public GetJobStatusResponseOutputMetadata $metadata;

    /**
     * @param array{
     *   data: GetJobStatusResponseOutputData,
     *   metadata: GetJobStatusResponseOutputMetadata,
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
