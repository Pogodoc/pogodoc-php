<?php

namespace Pogodoc\Templates\Requests;

use Pogodoc\Templates\Types\SaveCreatedTemplateRequestTemplateInfo;
use Pogodoc\Core\JsonProperty;
use Pogodoc\Templates\Types\SaveCreatedTemplateRequestPreviewIds;

class SaveCreatedTemplateRequest
{
    /**
     * @var SaveCreatedTemplateRequestTemplateInfo $templateInfo
     */
    #[JsonProperty('templateInfo')]
    public SaveCreatedTemplateRequestTemplateInfo $templateInfo;

    /**
     * @var SaveCreatedTemplateRequestPreviewIds $previewIds
     */
    #[JsonProperty('previewIds')]
    public SaveCreatedTemplateRequestPreviewIds $previewIds;

    /**
     * @param array{
     *   templateInfo: SaveCreatedTemplateRequestTemplateInfo,
     *   previewIds: SaveCreatedTemplateRequestPreviewIds,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->templateInfo = $values['templateInfo'];
        $this->previewIds = $values['previewIds'];
    }
}
