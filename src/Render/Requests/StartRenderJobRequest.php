<?php

namespace Pogodoc\Render\Requests;

use Pogodoc\Core\JsonProperty;

class StartRenderJobRequest
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
        array $values,
    ) {
        $this->shouldWaitForRenderCompletion = $values['shouldWaitForRenderCompletion'] ?? null;
        $this->uploadPresignedS3Url = $values['uploadPresignedS3Url'] ?? null;
    }
}
