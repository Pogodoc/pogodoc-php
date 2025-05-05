<?php

namespace Pogodoc\Templates\Requests;

use Pogodoc\Templates\Types\UpdateTemplateRequestTemplateInfo;
use Pogodoc\Core\JsonProperty;
use Pogodoc\Templates\Types\UpdateTemplateRequestPreviewIds;

class UpdateTemplateRequest
{
    /**
     * @var UpdateTemplateRequestTemplateInfo $templateInfo
     */
    #[JsonProperty('templateInfo')]
    public UpdateTemplateRequestTemplateInfo $templateInfo;

    /**
     * @var UpdateTemplateRequestPreviewIds $previewIds
     */
    #[JsonProperty('previewIds')]
    public UpdateTemplateRequestPreviewIds $previewIds;

    /**
     * @var string $contentId
     */
    #[JsonProperty('contentId')]
    public string $contentId;

    /**
     * @param array{
     *   templateInfo: UpdateTemplateRequestTemplateInfo,
     *   previewIds: UpdateTemplateRequestPreviewIds,
     *   contentId: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->templateInfo = $values['templateInfo'];
        $this->previewIds = $values['previewIds'];
        $this->contentId = $values['contentId'];
    }
}
