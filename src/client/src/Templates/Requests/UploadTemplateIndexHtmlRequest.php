<?php

namespace Pogodoc\Templates\Requests;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class UploadTemplateIndexHtmlRequest extends JsonSerializableType
{
    /**
     * @var string $indexHtml New index.html file of the template
     */
    #[JsonProperty('indexHtml')]
    public string $indexHtml;

    /**
     * @param array{
     *   indexHtml: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->indexHtml = $values['indexHtml'];
    }
}
