<?php

namespace Pogodoc\Templates\Types;

use Pogodoc\Core\Json\JsonSerializableType;
use Pogodoc\Core\Json\JsonProperty;
use Pogodoc\Core\Types\ArrayType;

class UpdateTemplateRequestTemplateInfo extends JsonSerializableType
{
    /**
     * @var ?string $title Title of the template
     */
    #[JsonProperty('title')]
    public ?string $title;

    /**
     * @var ?string $description Description of the template
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?value-of<UpdateTemplateRequestTemplateInfoType> $type Type of template to be rendered
     */
    #[JsonProperty('type')]
    public ?string $type;

    /**
     * @var ?array<string, mixed> $sampleData Sample data for the template
     */
    #[JsonProperty('sampleData'), ArrayType(['string' => 'mixed'])]
    public ?array $sampleData;

    /**
     * @var ?string $sourceCode
     */
    #[JsonProperty('sourceCode')]
    public ?string $sourceCode;

    /**
     * @var ?array<value-of<UpdateTemplateRequestTemplateInfoCategoriesItem>> $categories Categories of the template
     */
    #[JsonProperty('categories'), ArrayType(['string'])]
    public ?array $categories;

    /**
     * @var ?value-of<UpdateTemplateRequestTemplateInfoOrientation> $orientation
     */
    #[JsonProperty('orientation')]
    public ?string $orientation;

    /**
     * @var ?UpdateTemplateRequestTemplateInfoDimensions $dimensions
     */
    #[JsonProperty('dimensions')]
    public ?UpdateTemplateRequestTemplateInfoDimensions $dimensions;

    /**
     * @param array{
     *   title?: ?string,
     *   description?: ?string,
     *   type?: ?value-of<UpdateTemplateRequestTemplateInfoType>,
     *   sampleData?: ?array<string, mixed>,
     *   sourceCode?: ?string,
     *   categories?: ?array<value-of<UpdateTemplateRequestTemplateInfoCategoriesItem>>,
     *   orientation?: ?value-of<UpdateTemplateRequestTemplateInfoOrientation>,
     *   dimensions?: ?UpdateTemplateRequestTemplateInfoDimensions,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->title = $values['title'] ?? null;
        $this->description = $values['description'] ?? null;
        $this->type = $values['type'] ?? null;
        $this->sampleData = $values['sampleData'] ?? null;
        $this->sourceCode = $values['sourceCode'] ?? null;
        $this->categories = $values['categories'] ?? null;
        $this->orientation = $values['orientation'] ?? null;
        $this->dimensions = $values['dimensions'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
