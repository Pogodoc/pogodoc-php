<?php

namespace Pogodoc\Documents\Requests;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class StartRenderJobRequest extends JsonSerializableType
{
    /**
     * @var ?bool $shouldWaitForRenderCompletion
     */
    #[JsonProperty('shouldWaitForRenderCompletion')]
    public ?bool $shouldWaitForRenderCompletion;

    /**
     * @var ?string $uploadPresignedS3Url
     */
    #[JsonProperty('uploadPresignedS3Url')]
    public ?string $uploadPresignedS3Url;

    /**
     * @param array{
     *   shouldWaitForRenderCompletion?: ?bool,
     *   uploadPresignedS3Url?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->shouldWaitForRenderCompletion = $values['shouldWaitForRenderCompletion'] ?? null;
        $this->uploadPresignedS3Url = $values['uploadPresignedS3Url'] ?? null;
    }
}
