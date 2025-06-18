<?php

namespace Pogodoc\Templates\Requests;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;

class UploadTemplateIndexHtmlRequest extends JsonSerializableType
{
    /**
     * @var string $templateIndex New index.html file of the template
     */
    #[JsonProperty('templateIndex')]
    public string $templateIndex;

    /**
     * @param array{
     *   templateIndex: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->templateIndex = $values['templateIndex'];
    }
}
