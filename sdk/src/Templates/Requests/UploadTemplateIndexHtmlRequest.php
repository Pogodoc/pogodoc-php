<?php

namespace Pogodoc\Templates\Requests;

use Pogodoc\Core\JsonProperty;

class UploadTemplateIndexHtmlRequest
{
    /**
     * @var string $templateIndex
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
