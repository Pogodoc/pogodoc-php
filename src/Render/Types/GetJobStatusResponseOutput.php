<?php

namespace Pogodoc\Render\Types;

use Pogodoc\Core\SerializableType;
use Pogodoc\Core\JsonProperty;

class GetJobStatusResponseOutput extends SerializableType
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
}
