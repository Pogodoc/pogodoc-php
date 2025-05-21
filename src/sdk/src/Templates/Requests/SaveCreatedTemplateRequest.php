<?php

namespace Pogodoc\Templates\Requests;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Templates\Types\SaveCreatedTemplateRequestTemplateInfo;
use Pogodoc\Core\Json\JsonProperty;
use Pogodoc\Templates\Types\SaveCreatedTemplateRequestPreviewIds;

class SaveCreatedTemplateRequest extends JsonSerializableType
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
